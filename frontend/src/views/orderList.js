import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
import { ErrorBoundary } from '../config/bugsnag';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import OrderListContainer from '../containers/OrderListContainer';
import rootReducer from '../reducers';

const store = createStore(rootReducer, applyMiddleware(thunk));

ReactDOM.render(
  <Provider store={store}>
    <Router>
      <ErrorBoundary>
        <Route
          path="/"
          render={props => <OrderListContainer store={store} {...props} />}
        />
      </ErrorBoundary>
    </Router>
  </Provider>,
  document.getElementById('order-list')
);
