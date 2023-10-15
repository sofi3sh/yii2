import { SET_DATA_FROM_API } from '../actions';

const initialState = {
  clients: [],
  user: {},
  translations: [],
  order: {
    product: {},
    orderProductOptions: {}
  },
  orders: {}
};

export const setDataFromApiSuccess = data => ({
  type: SET_DATA_FROM_API,
  payload: data
});

const orderData = (state = initialState, action) => {
  switch (action.type) {
    case SET_DATA_FROM_API:
      return {
        ...state,
        ...action.payload
      };
    default:
      return state;
  }
};

export default orderData;
