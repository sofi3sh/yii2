import React, { Component } from 'react';

import OrderForm from '../components/OrderForm';
import {
  Basic,
  InternalDrainageParams,
  HydraulicSplitGrilleParams,
  BridgeTrayFiles,
  SaveForm
} from '../components/wizzard';
import CONFIG from '../config/app';
import Translate from '../components/Translate';
import { setDataFromApiSuccess } from '../reducers/orderData';
import withData from '../hocs/withData';

const getOrderIdIfUpdateAction = () => {
  const path = window.location.pathname.split('/');
  const lastPathItem = path.slice(-1).pop();
  if (Number(lastPathItem)) {
    return lastPathItem;
  }

  return null;
};

const getOrderFormContainer = () => {
  const orderId = getOrderIdIfUpdateAction();
  if (orderId) {
    return withData(['view'], {
      view: {
        params: {
          id: orderId
        },
        controller: 'order-form'
      }
    })(OrderFormContainer);
  }
  return OrderFormContainer;
};

export const convertLengthToUserMeasurementUnit = (value, measurementUnit = 'inch') => {
  let convertedValue = value;
  if (measurementUnit === 'inch') {
    convertedValue = value * 0.0393700787; // 1 mm = 0.0393700787 inch
  }

  return +Number(convertedValue).toFixed(2);
};

class OrderFormContainer extends Component {
  componentDidMount() {
    const orderId = getOrderIdIfUpdateAction();
    if (orderId) {
      this.props.store.dispatch(
        setDataFromApiSuccess({ isUpdateAction: true })
      );
    }
  }

  getTranslation({ titleSourceMessage, titleSourceMessage: { translations } }) {
    return (
      (translations.length && translations[0].translation) ||
      titleSourceMessage.message
    );
  }

  mapOptionsToDropdownProps({ data, dropdownValue }) {
    return Object.values(data).map(option => {
      const { translations } = option.titleSourceMessage;
      const label =
        (translations.length && translations[0].translation) ||
        option.titleSourceMessage.message;
      return {
        value: option[dropdownValue],
        label: label
      };
    });
  }

  getStepName = name => <Translate>{name}</Translate>;

  getWizzardSteps = props => {
    if (
      props[CONFIG.PRODUCT_OPTIONS.PRODUCT_KEY] == CONFIG.PRODUCTS.BRIDGE_TRAY
    ) {
      return [
        {
          name: this.getStepName('Create an order'),
          component: <Basic {...props} />
        },
        {
          name: this.getStepName('Bridge Tray Options'),
          component: <BridgeTrayFiles {...props} />
        },
        {
          name: this.getStepName('Save The Order'),
          component: <SaveForm {...props} />
        }
      ];
    }

    return [
      {
        name: this.getStepName('Create an order'),
        component: <Basic {...props} />
      },
      {
        name: this.getStepName('Drainage Options'),
        component: <InternalDrainageParams {...props} />
      },
      {
        name: this.getStepName('Grate Options'),
        component: <HydraulicSplitGrilleParams {...props} />
      },
      {
        name: this.getStepName('Save The Order'),
        component: <SaveForm {...props} />
      }
    ];
  };

  render() {
    return (
      <OrderForm
        {...this.props}
        mapOptionsToDropdownProps={this.mapOptionsToDropdownProps}
        getTranslation={this.getTranslation}
        getWizzardSteps={this.getWizzardSteps}
        getOrderIdIfUpdateAction={getOrderIdIfUpdateAction}
        convertLengthToUserMeasurementUnit={
          convertLengthToUserMeasurementUnit
        }
      />
    );
  }
}

export default getOrderFormContainer();
