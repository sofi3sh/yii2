import React from 'react';
import axios from 'axios';
import { connect } from 'react-redux';

import axiosConfig from '../config/axios';
import { setDataFromApiSuccess } from '../reducers/orderData';

const getData = (endpoints, requestParams = {}, props) => {
  const apiRequests = [];
  endpoints.forEach(endpoint => {
    //eslint-disable-next-line no-prototype-builtins
    if (
      (requestParams &&
        requestParams[endpoint] &&
        requestParams[endpoint]['params']) ||
      !props.hasOwnProperty(endpoint) ||
      !Object.values(props[endpoint]).length
    ) {
      const { [endpoint]: endpointParams } = requestParams;
      let params = {};

      if (endpointParams && endpointParams.params) {
        params = endpointParams.params;
      }
      if (typeof params === 'function') {
        params = params();
      }
      const controller = endpointParams
        ? endpointParams.controller
        : 'order-form';
      apiRequests.push(
        axiosConfig.get(`/${controller}/${endpoint}`, { params })
      );
    }
  });

  axios
    .all(apiRequests)
    .then(
      axios.spread((...result) => {
        result.forEach(({ data }) => {
          props.setDataFromApiSuccess(data);
        });
      })
    )
    .catch(err => {
      console.log(`Invalid endpoint: ${err.message}`);
    });
};

const mapStateToProps = ({ orderData }) => ({ ...orderData });

const mapDispatchToProps = dispatch => ({
  setDataFromApiSuccess: data => {
    dispatch(setDataFromApiSuccess(data));
  }
});

const withData = (endpoints, params) => WrappedComponent => {
  class WithDataComponent extends React.Component {
    componentDidMount() {
      getData(endpoints, params, this.props);
    }

    componentDidUpdate({ location }) {
      if (!location) {
        return;
      }
      if (location.search !== this.props.location.search) {
        getData(endpoints, params, this.props);
      }
    }

    render() {
      return <WrappedComponent {...this.props} />;
    }
  }

  return connect(
    mapStateToProps,
    mapDispatchToProps
  )(WithDataComponent);
};

export default withData;
