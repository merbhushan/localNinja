import { UserAgent } from "src/agent";

export async function users({ commit }, params = {}) {
  try {
    let response = await UserAgent.getUsers(params);
    return response;
  } catch (error) {
    return error;
  }
}

export async function createUser({ commit }, params = {}) {
  try {
    let response = await UserAgent.createUser(params);
    console.log(response);
    return response;
  } catch (error) {
    return error;
  }
}

export async function deleteUser({ commit }, userId) {
  try {
    let response = await UserAgent.deleteUser(userId);
    console.log(response.data);
    return response;
  } catch (error) {
    return error;
  }
}
