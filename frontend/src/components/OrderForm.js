import React from 'react';
import { connect } from 'react-redux';
import {
  reduxForm,
  formValueSelector,
  change,
  submit,
  valid
} from 'redux-form';

import StepZilla from './wizzard/StepZilla';
import withData from '../hocs/withData';
import CONFIG from '../config/app';
import axiosConfig from '../config/axios';

const isLastStep = (currentStep, wizzardSteps) => {
  return currentStep == wizzardSteps.length - 1;
};

const OrderForm = props => {
  const wizzardSteps = props.getWizzardSteps(props);
  return (
    <div className="card">
      <div className="card-body">
        <form>
          <StepZilla
            steps={wizzardSteps}
            backButtonText={props.translations.Previous}
            nextButtonText={props.translations.Next}
            preventEnterSubmission
            prevBtnOnLastStep={false}
            backButtonCls="btn btn-lg btn-primary mr-3"
            onStepChange={step => {
              if (isLastStep(step, wizzardSteps)) {
                props.dispatch(submit('order'));
              }
            }}
          />
        </form>
      </div>
    </div>
  );
};

OrderForm.defaultProps = {
  user: {}
};

export { OrderForm as PureOrderForm };
const orderId = window.location.pathname.split('/').pop();
const hydraulicGrille = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_GRILLE;
const hydraulicGrilleType = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_GRILLE_TYPE;
const hydraulicGrilleTypeNonStandard =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_GRILLE_NON_STANDARD_FILE;
const productKey = CONFIG.PRODUCT_OPTIONS.PRODUCT_KEY;
const internalDrainage = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;
const hydraulicTraySlope = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_TRAY_SLOPE;
const hydraulicTraySlopeCheckbox =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_TRAY_SLOPE_CHECKBOX;
const hydraulicSplit = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_SPLIT;
const euro100 = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_EURO_100;
const heightMinCheckbox = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_HEIGHT_MIN_CHECKBOX;
const heightMin = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_HEIGHT_MIN;
const heightMaxCheckbox = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_HEIGHT_MAX_CHECKBOX;
const heightMax = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_HEIGHT_MAX;
const hydraulicTrayLength = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_TRAY_LENGTH;
const hydraulicConnectionType =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_CONNECTION_TYPE;
const hydraulicFlange = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_FLANGE;
const hydraulicDrainageType = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_DRAINAGE_TYPE;
const hydraulicTubularOutput = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_TUBULAR_OUTPUT;
const hydraulicReleaseDirection =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_RELEASE_DIRECTION;
const hydraulicReleaseDirectionLeft =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_RELEASE_DIRECTION_LEFT;
const hydraulicReleasePlacement =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_RELEASE_PLACEMENT;
const hydraulicReleasePlacementEnd =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_RELEASE_PLACEMENT_END;
const hydraulicWaterSeal = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_WATER_SEAL;
const hydraulicWaterSealAndCatcher =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_WATER_SEAL_AND_CATCHER;
const clientId = CONFIG.PRODUCT_OPTIONS.CLIENT_ID;
const bridgeTrayFile = CONFIG.PRODUCT_OPTIONS.BRIDGE_TRAY_FILE;
const allowFragments = CONFIG.PRODUCT_OPTIONS.ALLOW_FRAGMENTS;
const grilleAdjustmentByCustomer =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER;
const grilleAdjustmentByManufacture =
  CONFIG.PRODUCT_OPTIONS.HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE;
const outfallDiametr = CONFIG.PRODUCT_OPTIONS.OUTFALL_DIAMETR;
const outfallDiametr100 = CONFIG.PRODUCT_OPTIONS.OUTFALL_DIAMETR_100;
const noEndLidInBeginningOfLine =
  CONFIG.PRODUCT_OPTIONS.NO_END_LID_IN_BEGINNING_OF_LINE;

const stringToBoolean = value => value === 'true';

const stringIsBoolean = value => value === 'true' || value === 'false';

const formWithData = withData(
  ['user', 'translations', 'files', 'dynamic-options'],
  {
    files: {
      params: {
        orderId
      },
      controller: 'order-form'
    },
    'dynamic-options': {
      params: {
        productId: CONFIG.PRODUCT_IDS.INTERNAL_DRAINAGE
      },
      controller: 'order-form'
    }
  }
)(OrderForm);
const selector = formValueSelector('order');
const getFieldInitialValue = ({
  state: {
    orderData: {
      order: { orderProductOptions }
    }
  },
  optionKey,
  defaultValue,
  optionValue = 'option_key'
}) => {
  if (
    orderProductOptions &&
    orderProductOptions[optionKey] &&
    typeof orderProductOptions[optionKey] === 'object'
  ) {
    return orderProductOptions[optionKey][optionValue];
  }
  if (orderProductOptions && stringIsBoolean(orderProductOptions[optionKey])) {
    return stringToBoolean(orderProductOptions[optionKey]);
  }
  return (
    (orderProductOptions && orderProductOptions[optionKey]) || defaultValue
  );
};
const mapStateToProps = state => {
  const productOptions = {
    change,
    valid,
    submitSucceeded: state.submitSucceeded,
    [hydraulicGrille]: selector(state, hydraulicGrille),
    [hydraulicGrilleType]: selector(state, hydraulicGrilleType),
    [hydraulicGrilleTypeNonStandard]: selector(
      state,
      hydraulicGrilleTypeNonStandard
    ),
    [productKey]: selector(state, productKey),
    [hydraulicTraySlope]: selector(state, hydraulicTraySlope),
    [hydraulicSplit]: selector(state, hydraulicSplit),
    [heightMin]: selector(state, heightMin),
    [heightMax]: selector(state, heightMax),
    [hydraulicTrayLength]: selector(state, hydraulicTrayLength),
    [hydraulicTraySlopeCheckbox]: selector(state, hydraulicTraySlopeCheckbox),
    [heightMinCheckbox]: selector(state, heightMinCheckbox),
    [heightMaxCheckbox]: selector(state, heightMaxCheckbox),
    [hydraulicWaterSeal]: selector(state, hydraulicWaterSeal),
    [hydraulicWaterSealAndCatcher]: selector(
      state,
      hydraulicWaterSealAndCatcher
    ),
    [bridgeTrayFile]: selector(state, bridgeTrayFile),
    [hydraulicConnectionType]: selector(state, hydraulicConnectionType),
    [allowFragments]: selector(state, allowFragments),
    [grilleAdjustmentByCustomer]: selector(state, grilleAdjustmentByCustomer),
    [grilleAdjustmentByManufacture]: selector(
      state,
      grilleAdjustmentByManufacture
    ),
    [noEndLidInBeginningOfLine]: selector(state, noEndLidInBeginningOfLine),
    [hydraulicDrainageType]: selector(state, hydraulicDrainageType),
    [hydraulicReleaseDirection]: selector(state, hydraulicReleaseDirection),
    orderData: state.orderData,
    [outfallDiametr]: selector(state, outfallDiametr),
    initialValues: {
      user_full_name: state.orderData.user.full_name,
      [hydraulicGrille]: getFieldInitialValue({
        state,
        optionKey: hydraulicGrille,
        defaultValue: true
      }),
      [productKey]:
        (state.orderData.order.product &&
          state.orderData.order.product.product_key) ||
        internalDrainage,
      [hydraulicTraySlope]: getFieldInitialValue({
        state,
        optionKey: hydraulicTraySlope,
        optionValue: 'product_option_value'
      }),
      [hydraulicSplit]: getFieldInitialValue({
        state,
        optionKey: hydraulicSplit,
        defaultValue: euro100
      }),
      [heightMin]:  getFieldInitialValue({
        state,
        optionKey: heightMin,
        defaultValue: ''
      }),
      [heightMax]: getFieldInitialValue({
        state,
        optionKey: heightMax,
        defaultValue: ''
      }),
      [hydraulicConnectionType]: getFieldInitialValue({
        state,
        optionKey: hydraulicConnectionType,
        defaultValue: hydraulicFlange
      }),
      [hydraulicDrainageType]: getFieldInitialValue({
        state,
        optionKey: hydraulicDrainageType,
        defaultValue: hydraulicTubularOutput
      }),
      [hydraulicReleaseDirection]: getFieldInitialValue({
        state,
        optionKey: hydraulicReleaseDirection,
        defaultValue: hydraulicReleaseDirectionLeft
      }),
      [hydraulicReleasePlacement]: hydraulicReleasePlacementEnd,
      [hydraulicWaterSeal]: getFieldInitialValue({
        state,
        optionKey: hydraulicWaterSeal,
        defaultValue: false
      }),
      [hydraulicWaterSealAndCatcher]: getFieldInitialValue({
        state,
        optionKey: hydraulicWaterSealAndCatcher,
        defaultValue: false
      }),
      [hydraulicTrayLength]: getFieldInitialValue({
        state,
        optionKey: hydraulicTrayLength,
        defaultValue: ''
      }),
      [clientId]:
        (state.orderData.order && state.orderData.order.client_id) || null,
      [bridgeTrayFile]: getFieldInitialValue({
        state,
        optionKey: bridgeTrayFile,
        defaultValue: null
      }),
      [hydraulicGrilleTypeNonStandard]: getFieldInitialValue({
        state,
        optionKey: hydraulicGrilleTypeNonStandard,
        defaultValue: null
      }),
      [allowFragments]:
        (state.orderData.order && +state.orderData.order.allow_fragments) ||
        null,
      [grilleAdjustmentByCustomer]: getFieldInitialValue({
        state,
        optionKey: grilleAdjustmentByCustomer,
        defaultValue: false
      }),
      [grilleAdjustmentByManufacture]: getFieldInitialValue({
        state,
        optionKey: grilleAdjustmentByManufacture,
        defaultValue: false
      }),
      [outfallDiametr]: getFieldInitialValue({
        state,
        optionKey: outfallDiametr,
        defaultValue: outfallDiametr100
      }),
      [noEndLidInBeginningOfLine]: getFieldInitialValue({
        state,
        optionKey: noEndLidInBeginningOfLine,
        defaultValue: false
      })
    }
  };

  const {
    orderData: { dynamicOptions }
  } = state;

  if (!dynamicOptions) {
    return productOptions;
  }

  dynamicOptions.forEach(option => {
    if (!productOptions.hasOwnProperty(option.option_key)) {
      productOptions[option.option_key] = selector(state, option.option_key);
      productOptions.initialValues[option.option_key] = getFieldInitialValue({
        state,
        optionKey: option.option_key,
        defaultValue: option.value
      });
    }
  });

  return productOptions;
};

const makeSubmitRequest = ({
  props: { getOrderIdIfUpdateAction },
  requestData
}) => {
  const orderId = getOrderIdIfUpdateAction();
  let actionName = 'create';
  if (orderId) {
    requestData.append('id', orderId);
    actionName = 'update';
  }
  return axiosConfig.post(`order-form/${actionName}`, requestData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  });
};

const onSubmit = (values, dispatch, props) => {
  const dynamicInternalDrainageOptions = props.orderData.dynamicOptions.map(
    ({ option_key }) => option_key
  );

  const staticInternalDrainageOptions = [
    hydraulicSplit,
    heightMin,
    heightMax,
    hydraulicTrayLength,
    hydraulicTraySlope,
    hydraulicConnectionType,
    hydraulicDrainageType,
    hydraulicReleasePlacement,
    hydraulicWaterSeal,
    hydraulicWaterSealAndCatcher,
    hydraulicReleaseDirection,
    hydraulicGrille,
    outfallDiametr,
    hydraulicGrilleType,
    grilleAdjustmentByCustomer,
    grilleAdjustmentByManufacture,
    noEndLidInBeginningOfLine
  ];

  const mergedOptions = staticInternalDrainageOptions.concat(
    dynamicInternalDrainageOptions
  );

  const internalDrainageOptionsList = mergedOptions.filter(
    (item, position) => mergedOptions.indexOf(item) === position
  );

  const dynamicFileOptionsKeys = props.orderData.dynamicOptions
    .filter(option => option.option_type === CONFIG.PRODUCT_OPTION_TYPES.FILE)
    .map(option => option.option_key);

  const filesOptionsList = [
    bridgeTrayFile,
    hydraulicGrilleTypeNonStandard,
    ...dynamicFileOptionsKeys
  ];
  const formData = new FormData();
  const selectedProductKey = values[productKey];
  formData.append(clientId, values[clientId]);
  formData.append(productKey, selectedProductKey);
  formData.append(allowFragments, +values[allowFragments]);

  const productOptions = { [selectedProductKey]: {} };

  if (values[productKey] === internalDrainage) {
    internalDrainageOptionsList.map(optionKey => {
      productOptions[internalDrainage][optionKey] = values[optionKey];
    });
  }

  filesOptionsList.map(optionKey => {
    if (values[optionKey]) {
      formData.append(optionKey, values[optionKey][0]);
    }
  });

  formData.append(
    selectedProductKey,
    JSON.stringify(productOptions[selectedProductKey])
  );
  makeSubmitRequest({
    props,
    requestData: formData
  });
};

export default connect(mapStateToProps)(
  reduxForm({ form: 'order', enableReinitialize: true, onSubmit })(formWithData)
);
