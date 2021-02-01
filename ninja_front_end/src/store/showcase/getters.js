export const getDialogState = (state) => (dialog) => {
  return state.dialog[dialog]
}

export const getDialogProperties = (state) => (dialog) => {
  return state.properties[dialog] || {}
}
