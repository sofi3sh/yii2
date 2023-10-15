import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
import { ErrorBoundary } from '../../config/bugsnag';

import OrderFormContainer from '../../containers/OrderFormContainer';
import rootReducer from '../../reducers';

const store = createStore(rootReducer, applyMiddleware(thunk));

ReactDOM.render(
  <Provider store={store}>
    <ErrorBoundary>
      <OrderFormContainer store={store} />
    </ErrorBoundary>
  </Provider>,
  document.getElementById('order-form')
);
