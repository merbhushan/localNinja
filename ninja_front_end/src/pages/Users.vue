<template>
  <div class="q-pa-md">
    <q-table
      class="my-sticky-header-table"
      title="Ninja Users"
      :data="data"
      :columns="columns"
      row-key="id"
      :pagination.sync="pagination"
      :loading="loading"
      :filter="filter"
      @request="onRequest"
      style="background-color: #0D223B; color: white;"
      flat
    >
      <template v-slot:top-right>
        <q-btn
          color="white"
          text-color="black"
          label="Add New"
          class="q-mr-md"
          @click="
            $store.commit('showcase/updateDialogState', {
              dialog: 'createNinjaUser',
              val: true
            })
          "
        />
        <q-input
          borderless
          dense
          debounce="300"
          v-model="filter"
          placeholder="Search"
          dark
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:body-cell-action="props">
        <q-td :props="props">
          <div class="q-pl-xl">
            <q-btn flat color="standard" round icon="edit" />
            <q-btn flat color="standard" round icon="delete" />
          </div>
        </q-td>
      </template>
    </q-table>
    <CreateUserDialog />
    <CaptureImage />
  </div>
</template>

<script>
import CreateUserDialog from "src/components/Dialog/CreateUser";
import CaptureImage from "src/components/Dialog/CaptureImage";

export default {
  components: { CreateUserDialog, CaptureImage },
  data() {
    return {
      filter: "",
      loading: false,
      pagination: {
        page: 1,
        rowsPerPage: 20,
        rowsNumber: 10
      },
      columns: [
        {
          name: "id",
          required: true,
          label: "#ID",
          align: "center",
          field: "id"
        },
        {
          name: "name",
          align: "left",
          label: "Name",
          field: "name",
          sortable: true
        },
        {
          name: "action",
          required: true,
          label: "Action",
          align: "left"
        }
      ],
      data: []
    };
  },
  mounted() {
    // get initial data from server (1st page)
    this.onRequest({
      pagination: this.pagination,
      filter: undefined
    });
  },
  methods: {
    onRequest(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      console.log(sortBy, descending);

      this.loading = true;
      let params = { page, per_page: rowsPerPage, filter };
      if (sortBy) {
        params.sortBy = {
          sortBy,
          descending
        };
      }
      this.$store.dispatch("user/users", params).then(response => {
        this.loading = false;

        if (response.status === 200) {
          // clear out existing data and add new
          this.data.splice(0, this.data.length, ...response.data.data);

          // don't forget to update local pagination object
          this.pagination.page = response.data.current_page;
          this.pagination.rowsPerPage = response.data.per_page;
          this.pagination.rowsNumber = response.data.total;
          //   sortBy: "desc",
          // descending: false,
          this.pagination.sortBy = sortBy;
          this.pagination.descending = descending;
          console.log("pagination", this.pagination);
        }
        // console.log("response", response.data.data);
      });
      return;
    },

    // emulate ajax call
    // SELECT * FROM ... WHERE...LIMIT...
    fetchFromServer(startRow, count, filter, sortBy, descending) {
      const data = filter
        ? this.original.filter(row => row.name.includes(filter))
        : this.original.slice();

      // handle sortBy
      if (sortBy) {
        const sortFn =
          sortBy === "desc"
            ? descending
              ? (a, b) => (a.name > b.name ? -1 : a.name < b.name ? 1 : 0)
              : (a, b) => (a.name > b.name ? 1 : a.name < b.name ? -1 : 0)
            : descending
            ? (a, b) => parseFloat(b[sortBy]) - parseFloat(a[sortBy])
            : (a, b) => parseFloat(a[sortBy]) - parseFloat(b[sortBy]);
        data.sort(sortFn);
      }

      return data.slice(startRow, startRow + count);
    },

    // emulate 'SELECT count(*) FROM ...WHERE...'
    getRowsNumberCount(filter) {
      if (!filter) {
        return this.original.length;
      }
      let count = 0;
      this.original.forEach(treat => {
        if (treat.name.includes(filter)) {
          ++count;
        }
      });
      return count;
    }
  }
};
</script>

<style lang="sass">
.my-sticky-header-table
  /* height or max-height is important */
  height: calc(100vh - 50px)

  .q-table__top,
  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #0D223B

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
