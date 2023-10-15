import { ADD_NOTIFICATION, REMOVE_NOTIFICATION } from '../actions';

const initialState = {};

export const addNotification = data => ({
  type: ADD_NOTIFICATION,
  payload: data
});

export const removeNotification = notificationName => ({
  type: REMOVE_NOTIFICATION,
  notificationName
});

const notifications = (state = initialState, action) => {
  switch (action.type) {
    case ADD_NOTIFICATION:
      return {
        ...state,
        ...action.payload
      };
    case REMOVE_NOTIFICATION:
      const newState = { ...state };
      delete newState[action.notificationName];
      return newState;
    default:
      return state;
  }
};

export default notifications;
