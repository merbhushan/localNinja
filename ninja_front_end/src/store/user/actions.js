import { UserAgent } from "src/agent";

export async function users({ commit }, params = {}) {
  try {
    let response = await UserAgent.getUsers(params);
    return response;
  } catch (error) {
    return error;
  }
}
