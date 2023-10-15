import { combineReducers } from 'redux';
import { reducer as formReducer } from 'redux-form';

import orderData from './orderData';
import notifications from './notifications';

export default combineReducers({
  orderData,
  notifications,
  form: formReducer
});
