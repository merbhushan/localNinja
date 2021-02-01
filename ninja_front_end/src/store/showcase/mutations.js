import Vue from 'vue'

export const updateDialogState = (state, { dialog, val, parentDialog }) => {
  if (parentDialog && !state.parentDialogs.includes(dialog)) {
    state.parentDialogs.push(parentDialog)
  }

  if (state.parentDialogs.includes(dialog)) {
    state.parentDialogs.pop()
  }

  if (val) {
    for (let key in state.dialog) {
      if (key == dialog) state.dialog[key] = val
      else if (!state.parentDialogs.includes(key)) state.dialog[key] = false
    }
  } else state.dialog[dialog] = false
}

export const updateSidebarState = (state, val) => {
  state.sideBar = !state.sideBar
}

export const SET_DIALOG_PROPERTIES = (
  state,
  { dialog, properties, refresh = false }
) => {
  if (!state.properties[dialog]) {
    state.properties = Object.assign({}, state.properties, { [dialog]: {} })
  }
  if (refresh) {
    state.properties[dialog] = Object.assign({}, properties)
  } else {
    state.properties[dialog] = Object.assign(
      {},
      state.properties[dialog],
      properties
    )
  }
}

export const DELETE_DIALOG_PROPERTIES = (state, dialog) => {
  Vue.delete(state.properties, dialog)
}
