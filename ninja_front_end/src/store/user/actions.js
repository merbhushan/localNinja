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
    return response;
  } catch (error) {
    return error;
  }
}

export async function modifyUser({ commit }, {params, userId}) {
  try {
    let response = await UserAgent.updateUser(userId, params);
    return response;
  } catch (error) {
    return error;
  }
}

export async function deleteUser({ commit }, userId) {
  try {
    let response = await UserAgent.deleteUser(userId);
    if(response.data && response.data.code){
      return true
    }
  } catch (error) {
    console.log(error);
    return false;
  }
}
