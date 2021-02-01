<template>
  <div class="q-pa-md">
    <q-table
      title="Ninja Users"
      :data="data"
      :columns="columns"
      row-key="id"
      :pagination.sync="pagination"
      :loading="loading"
      :filter="filter"
      @request="onRequest"
    >
      <template v-slot:top-right>
        <q-input
          borderless
          dense
          debounce="300"
          v-model="filter"
          placeholder="Search"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>
    <CreateUserDialog />
  </div>
</template>

<script>
import CreateUserDialog from "src/components/Dialog/CreateUser";

export default {
  components: { CreateUserDialog },
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

      // emulate server
      setTimeout(() => {
        // update rowsCount with appropriate value
        this.pagination.rowsNumber = this.getRowsNumberCount(filter);

        // get all rows if "All" (0) is selected
        const fetchCount =
          rowsPerPage === 0 ? this.pagination.rowsNumber : rowsPerPage;

        // calculate starting row of data
        const startRow = (page - 1) * rowsPerPage;

        // fetch data from "server"
        const returnedData = this.fetchFromServer(
          startRow,
          fetchCount,
          filter,
          sortBy,
          descending
        );

        // clear out existing data and add new
        this.data.splice(0, this.data.length, ...returnedData);

        // don't forget to update local pagination object
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;

        // ...and turn of loading indicator
        this.loading = false;
      }, 1500);
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
