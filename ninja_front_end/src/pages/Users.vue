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
      dark
    >
      <template v-slot:no-data="{ icon, message, filter }">
        <div class="full-width row flex-center text-white q-gutter-sm">
          <q-icon size="2em" name="sentiment_dissatisfied" />
          <span> Well this is sad... {{ message }} </span>
          <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
        </div>
      </template>
      <template v-slot:top-right>
        <q-input
          dense
          outlined
          debounce="300"
          class="q-mr-md"
          v-model="filter"
          placeholder="Search"
          dark
          clearable
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>

        <q-btn
          color="grey"
          text-color="black"
          label="Add New"
          @click="
            () => {
              $store.commit('showcase/SET_DIALOG_PROPERTIES', {
                dialog: 'createNinjaUser',
                properties: {},
                refresh: true
              });
              $store.commit('showcase/updateDialogState', {
                dialog: 'createNinjaUser',
                val: true
              });
            }
          "
        />
      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <template v-for="(col, index) in props.cols">
            <q-td v-if="col.name == 'action'" style="text-align: center;">
              <q-btn
                flat
                color="standard"
                @click="editUser(props.row)"
                round
                icon="edit"
              />
              <q-btn
                flat
                color="standard"
                @click="deleteUser(props.row)"
                round
                icon="delete"
              />
            </q-td>
            <q-td v-else-if="col.name == 'avatar'" style="text-align: center;">
              <q-img
                :src="props.row.avatar_url"
                spinner-color="white"
                v-if="props.row.avatar_url"
                style="height: 35px; width: 35px; border-radius: 50%;"
              />
              <q-avatar
                v-else
                style="background-color: #dadada; color: #0d223b;"
                size="35px"
              >
                {{ props.row.name.charAt(0).toUpperCase() }}
              </q-avatar>
              <!--
              <q-img
                v-else-if="props.row.gender == 'Female'"
                src="/images/user-avatars/girl.png"
                spinner-color="white"
                style="height: 35px; width: 35px; border-radius: 50%;"
              />
              <q-img
                v-else
                src="/images/user-avatars/boy.png"
                spinner-color="white"
                style="height: 35px; width: 35px; border-radius: 50%;"
              /> -->
            </q-td>
            <q-td v-else>
              {{ col.value }}
            </q-td>
          </template>
        </q-tr>
      </template>
    </q-table>
    <CreateUserDialog :callback="createUserCallback" />
    <CaptureImage />
    <FullscreenLoader :showing="showFullLoading" />
  </div>
</template>

<script>
import CreateUserDialog from "src/components/Dialog/CreateUser";
import CaptureImage from "src/components/Dialog/CaptureImage";
import CustomConfirm from "src/components/Common/CustomConfirm";
import { mapGetters } from "vuex";

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
          align: "left",
          headerStyle: "width: 3%;",
          field: "id"
        },
        {
          name: "avatar",
          required: true,
          field: "name",
          label: "Avatar",
          headerStyle: "width: 5%;",
          align: "center",
          sortable: false
        },
        {
          name: "name",
          align: "left",
          label: "Name",
          headerStyle: "width: 80%;",
          field: "name",
          sortable: true
        },
        {
          name: "action",
          required: true,
          label: "Action",
          headerStyle: "text-align: center;",
          align: "center"
        }
      ],
      data: []
    };
  },
  computed: {
    ...mapGetters("common", ["showFullLoading"])
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
    deleteUser(data) {
      this.$q
        .dialog({
          component: CustomConfirm,
          parent: this,
          text: `Are you sure you want to remove ${data.name}?`
        })
        .onOk(() => {
          this.$store.commit("common/SET_FULL_LOADING", true);
          this.$store.dispatch("user/deleteUser", data.id).then(response => {
            if (response) {
              this.showSuccess("User removed successfully.");
              this.onRequest({
                filter: this.filter,
                pagination: this.pagination
              });
            } else {
              this.showError("Error occurred while removing a user.");
            }
            this.$store.commit("common/SET_FULL_LOADING", false);
          });
        });
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
    },

    editUser(data) {
      this.$store.commit("showcase/SET_DIALOG_PROPERTIES", {
        dialog: "createNinjaUser",
        properties: {
          user: data
        },
        refresh: true
      });
      this.$store.commit("showcase/updateDialogState", {
        dialog: "createNinjaUser",
        val: true
      });
    },

    createUserCallback() {
      this.onRequest({
        filter: this.filter,
        pagination: this.pagination
      });
    }
  }
};
</script>

<style lang="sass">
.my-sticky-header-table
  /* height or max-height is important */
  max-height: calc(100vh - 50px)
  max-width: 1280px
  margin: 0 auto

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
