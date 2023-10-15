import React, { Component } from 'react';

import HydraulicSplitOptionsDropdown from '../../reduxForm/HydraulicSplitOptionsDropdown';
import { InputWithoutMarkup } from '../../reduxForm/Input';
import InputWithCheckbox from '../../reduxForm/InputWithCheckbox';
import CONFIG from '../../../config/app';
import HydraulicConnectionTypeDropdown from '../../reduxForm/HydraulicConnectionTypeDropdown';
import Checkbox from '../../reduxForm/Checkbox';
import InputFile from '../../reduxForm/InputFile';
import HydraulicReleasePlacementDropdown from '../../reduxForm/HydraulicReleasePlacementDropdown';
import HydraulicDrainageTypeDropdown from '../../reduxForm/HydraulicDrainageTypeDropdown';
import HydraulicReleaseDirectionDropdown from '../../reduxForm/HydraulicReleaseDirectionDropdown';
import OutfallDiametrDropdown from '../../reduxForm/OutfallDiametrDropdown';
import DynamicField from '../../DynamicField';
import {
  number,
  required,
  valueBetweenRange,
  numberMultiplicity05,
  traySlopeAndTrayHeight,
  waterSealCheckboxes
} from '../../validation/validationRules';
import Translate from '../../Translate';
import Instruction from '../../Instruction';
import axiosConfig from '../../../config/axios';
import withData from '../../../hocs/withData';
import { Field } from 'redux-form';

const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;
const productOptionKeys = CONFIG.PRODUCT_OPTIONS;
const drainageTypeKey = productOptionKeys.HYDRAULIC_DRAINAGE_TYPE;

class InternalDrainageParams extends Component {
  state = {
    options: []
  };

  componentDidMount() {
    this.requestDynamicOptions();
    this.setTraySlopeDefaultValue(this.props.products);
    this.setHeightMinAndMax(this.props[productOptionKeys.HYDRAULIC_SPLIT]);
    this.setEuroHeight(this.props[productOptionKeys.HYDRAULIC_SPLIT]);
  }

  setHeightMinAndMax = hydraulicSplit => {
    this.setHeight(hydraulicSplit, productOptionKeys.HYDRAULIC_HEIGHT_MIN);
    this.setHeight(hydraulicSplit, productOptionKeys.HYDRAULIC_HEIGHT_MAX);
  };

  setHeight = (hydraulicSplit, inputKey) => {
    const {
      [internalDrainageKey]: {
        parentOptions: {
          [productOptionKeys.HYDRAULIC_SPLIT]: { childrenOptions }
        }
      }
    } = this.props.products;
    const selectedHydraulicSplitHeightKey = `${hydraulicSplit}_${inputKey}`;
    const {
      [hydraulicSplit]: {
        childrenOptions: {
          [selectedHydraulicSplitHeightKey]: hydraulicSplitHeight
        }
      }
    } = childrenOptions;
    const {
      order: {
        orderProductOptions: { [inputKey]: hydraulicSplitHeightUpdate }
      },
      convertLengthToUserMeasurementUnit,
      user: {
        measurementSystem: { defaultLengthUnit }
      }
    } = this.props;
    const hydraulicSplitHeightConvertedValue = convertLengthToUserMeasurementUnit(
      hydraulicSplitHeight.value,
      defaultLengthUnit
    );
    this.props.change(
      inputKey,
      this.getInitialFieldValue({
        defaultOption: {
          ...hydraulicSplitHeight,
          value: hydraulicSplitHeightConvertedValue
        },
        optionToUpdate: this.trimNumberToPrecision(hydraulicSplitHeightUpdate)
      })
    );

    return true;
  };

  /*
  Still using this approach because StepZilla
  doesn't work well with HOCs
  https://github.com/newbreedofgeek/react-stepzilla/issues/80
  */
  requestDynamicOptions = () => {
    axiosConfig
      .get(`/order-form/dynamic-options?productId=${CONFIG.PRODUCT_IDS.INTERNAL_DRAINAGE}`)
      .then(({ data: { dynamicOptions } }) => {
        const optionsSequence = this.getOptionsSequenceAsArray(dynamicOptions);

        return this.setState({
          options: optionsSequence
        });
      });
  };

  isNoDreinagingSelected = () =>
    this.props[drainageTypeKey] === productOptionKeys.HYDRAULIC_WITHOUT_OUTPUT;

  setEuroHeight(hydraulicSplitSelected) {
    if (!this.props.products) {
      return false;
    }

    const {
      [internalDrainageKey]: {
        parentOptions: {
          [productOptionKeys.HYDRAULIC_SPLIT]: {
            childrenOptions: hydraulicSplitOptions
          }
        }
      }
    } = this.props.products;

    const euroHeightMin =
      hydraulicSplitOptions[hydraulicSplitSelected]['childrenOptions'][
        `${hydraulicSplitSelected}_height_min`
      ]['value'];
    const euroHeightMax =
      hydraulicSplitOptions[hydraulicSplitSelected]['childrenOptions'][
        `${hydraulicSplitSelected}_height_max`
      ]['value'];
    this.props.change(
      productOptionKeys.HYDRAULIC_EURO_HEIGHT_MIN,
      euroHeightMin
    );
    this.props.change(
      productOptionKeys.HYDRAULIC_EURO_HEIGHT_MAX,
      euroHeightMax
    );
  }

  getInitialFieldValue({
    defaultOption,
    optionToUpdate,
    updateOptionKeyValue
  }) {
    let initValue = defaultOption.value;
    if (this.props.isUpdateAction && optionToUpdate) {
      initValue = optionToUpdate;
    }
    if (typeof optionToUpdate === 'object' && optionToUpdate) {
      initValue = optionToUpdate[updateOptionKeyValue];
    }
    return initValue;
  }

  filterOptionsOnNoDreinaging = () => {
    if (this.isNoDreinagingSelected()) {
      this.props.change(productOptionKeys.HYDRAULIC_RELEASE_DIRECTION, false);
      this.props.change(productOptionKeys.OUTFALL_DIAMETR, false);

      return;
    }

    !this.props.hydraulic_release_direction &&
      this.props.change(
        productOptionKeys.HYDRAULIC_RELEASE_DIRECTION,
        productOptionKeys.HYDRAULIC_RELEASE_DIRECTION_LEFT
      );

    !this.props.Outfall_diameter &&
      this.props.change(
        productOptionKeys.OUTFALL_DIAMETR,
        productOptionKeys.OUTFALL_DIAMETR_100);
  };

  setTraySlopeDefaultValue() {
    const {
      products: {
        [internalDrainageKey]: {
          parentOptions: { [productOptionKeys.HYDRAULIC_TRAY_SLOPE]: traySlope }
        }
      },
      order: {
        orderProductOptions: {
          [productOptionKeys.HYDRAULIC_TRAY_SLOPE]: traySlopeUpdate
        }
      }
    } = this.props;

    this.props.change(
      productOptionKeys.HYDRAULIC_TRAY_SLOPE,
      this.getInitialFieldValue({
        defaultOption: traySlope,
        optionToUpdate: traySlopeUpdate
      })
    );
  }

  setHeight(hydraulicSplit, inputKey) {
    const {
      [internalDrainageKey]: {
        parentOptions: {
          [productOptionKeys.HYDRAULIC_SPLIT]: { childrenOptions }
        }
      }
    } = this.props.products;
    const selectedHydraulicSplitHeightKey = `${hydraulicSplit}_${inputKey}`;
    const {
      [hydraulicSplit]: {
        childrenOptions: {
          [selectedHydraulicSplitHeightKey]: hydraulicSplitHeight
        }
      }
    } = childrenOptions;
    const {
      order: {
        orderProductOptions: { [inputKey]: hydraulicSplitHeightUpdate }
      },
      convertLengthToUserMeasurementUnit,
      user: {
        measurementSystem: { defaultLengthUnit }
      }
    } = this.props;
    const hydraulicSplitHeightConvertedValue = convertLengthToUserMeasurementUnit(
      hydraulicSplitHeight.value,
      defaultLengthUnit
    );
    this.props.change(
      inputKey,
      this.getInitialFieldValue({
        defaultOption: {
          ...hydraulicSplitHeight,
          value: hydraulicSplitHeightConvertedValue
        },
        optionToUpdate: this.trimNumberToPrecision(hydraulicSplitHeightUpdate)
      })
    );
    return true;
  }

  calculateHeightMaxFromHeightMinAndLength() {
    const {
      [productOptionKeys.HYDRAULIC_HEIGHT_MIN_CHECKBOX]: heightMinCheckbox,
      [productOptionKeys.HYDRAULIC_HEIGHT_MAX_CHECKBOX]: heightMaxCheckbox,
      [productOptionKeys.HYDRAULIC_TRAY_LENGTH]: trayLength,
      [productOptionKeys.HYDRAULIC_TRAY_SLOPE_CHECKBOX]: traySlopeCheckbox,
      [productOptionKeys.HYDRAULIC_TRAY_SLOPE]: traySlope,
      [productOptionKeys.HYDRAULIC_HEIGHT_MIN]: trayHeightMin
    } = this.props;

    if (
      heightMinCheckbox &&
      trayLength &&
      !heightMaxCheckbox &&
      !traySlopeCheckbox
    ) {
      const calculatedHeightMax =
        trayLength * (traySlope / 100) + +trayHeightMin;
      this.props.change(
        productOptionKeys.HYDRAULIC_HEIGHT_MAX,
        this.trimNumberToPrecision(calculatedHeightMax)
      );
    }
  }

  calculateTraySlopeFromHeightAndLength() {
    const {
      [productOptionKeys.HYDRAULIC_HEIGHT_MIN_CHECKBOX]: heightMinCheckbox,
      [productOptionKeys.HYDRAULIC_HEIGHT_MAX_CHECKBOX]: heightMaxCheckbox,
      [productOptionKeys.HYDRAULIC_TRAY_LENGTH]: trayLength,
      [productOptionKeys.HYDRAULIC_HEIGHT_MIN]: heightMin,
      [productOptionKeys.HYDRAULIC_HEIGHT_MAX]: heightMax
    } = this.props;

    if (heightMinCheckbox && heightMaxCheckbox && trayLength) {
      const calculatedTraySlope = ((heightMax - heightMin) / trayLength) * 100;
      this.props.change(
        productOptionKeys.HYDRAULIC_TRAY_SLOPE,
        calculatedTraySlope.toFixed(1)
      );
    }
  }

  calculateHeightMaxFromSlopeHeightMinLength() {
    const {
      [productOptionKeys.HYDRAULIC_HEIGHT_MIN_CHECKBOX]: heightMinCheckbox,
      [productOptionKeys.HYDRAULIC_HEIGHT_MAX_CHECKBOX]: heightMaxCheckbox,
      [productOptionKeys.HYDRAULIC_TRAY_LENGTH]: trayLength,
      [productOptionKeys.HYDRAULIC_HEIGHT_MIN]: heightMin,
      [productOptionKeys.HYDRAULIC_TRAY_SLOPE_CHECKBOX]: traySlopeCheckbox,
      [productOptionKeys.HYDRAULIC_TRAY_SLOPE]: traySlope
    } = this.props;

    if (traySlopeCheckbox && heightMinCheckbox && !heightMaxCheckbox) {
      const calculatedHeightMax = traySlope * (trayLength / 100) + +heightMin;
      this.props.change(
        productOptionKeys.HYDRAULIC_HEIGHT_MAX,
        this.trimNumberToPrecision(calculatedHeightMax)
      );
    }
  }

  calculateHeightMinFromSlopeHeightMaxLength() {
    const {
      [productOptionKeys.HYDRAULIC_HEIGHT_MIN_CHECKBOX]: heightMinCheckbox,
      [productOptionKeys.HYDRAULIC_HEIGHT_MAX_CHECKBOX]: heightMaxCheckbox,
      [productOptionKeys.HYDRAULIC_TRAY_LENGTH]: trayLength,
      [productOptionKeys.HYDRAULIC_TRAY_SLOPE_CHECKBOX]: traySlopeCheckbox,
      [productOptionKeys.HYDRAULIC_TRAY_SLOPE]: traySlope,
      [productOptionKeys.HYDRAULIC_HEIGHT_MAX]: heightMax
    } = this.props;

    if (traySlopeCheckbox && heightMaxCheckbox && !heightMinCheckbox) {
      const calculatedHeightMin = heightMax - (traySlope / 100) * trayLength;
      this.props.change(
        productOptionKeys.HYDRAULIC_HEIGHT_MIN,
        this.trimNumberToPrecision(calculatedHeightMin)
      );
    }
  }

  isValidated = () => {
    return this.props.valid;
  }

  onTrayLengthChange = () => {
    this.calculateHeightMaxFromHeightMinAndLength();
    this.calculateTraySlopeFromHeightAndLength();
    this.calculateHeightMaxFromSlopeHeightMinLength();
    this.calculateHeightMinFromSlopeHeightMaxLength();
  };

  formatNumberValue = (value = '') =>
    value && value.toString().replace(',', '.');

  trimNumberToPrecision = (value, precision = 2) =>
    Number(value).toFixed(precision);

  getSystemComponent = (key, props) => {
    const {
      products: {
        [internalDrainageKey]: { parentOptions }
      },
      user: {
        measurementSystem: { defaultLengthUnit }
      },
      getTranslation
    } = props;

    const staticComponents = {
      [productOptionKeys.HYDRAULIC_SPLIT]: (
        <HydraulicSplitOptionsDropdown
          {...this.props}
          onChange={({ target }) => {
            this.setHeightMinAndMax(target.value);
            this.setEuroHeight(target.value);
          }}
        />
      ),
      [productOptionKeys.HYDRAULIC_RELEASE_PLACEMENT]: (
        <HydraulicReleasePlacementDropdown {...this.props} />
      ),
      [productOptionKeys.HYDRAULIC_TRAY_LENGTH]: (
        <div className="row form-group align-items-center">
          <div className="col-2 col-form-label">
            <label htmlFor={productOptionKeys.HYDRAULIC_TRAY_LENGTH}>
              <Translate>
                {getTranslation(
                  parentOptions[productOptionKeys.HYDRAULIC_TRAY_LENGTH]
                )}
              </Translate>{' '}
              (A){' '}
              <Instruction message="Overall line length = tray length + ladder gauge (if selected) + end lid gauge" />
            </label>
            <div className="font-size-xs">
              <Translate>{`(measured in {${defaultLengthUnit}})`}</Translate>
            </div>
          </div>
          <div className="col-md-10 col-lg-10 col-sm-10 col-xs-10">
            <InputWithoutMarkup
              name={productOptionKeys.HYDRAULIC_TRAY_LENGTH}
              onInputBlur={this.onTrayLengthChange}
              validate={[number, required]}
              format={this.formatNumberValue}
            />
          </div>
          <div className="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <button
              className="btn btn-primary w-100 font-size-xs"
              onClick={event => {
                event.preventDefault();
                this.onTrayLengthChange();
              }}
            >
              <Translate>Calculate</Translate>
            </button>
          </div>
        </div>
      ),
      [productOptionKeys.HYDRAULIC_TRAY_SLOPE]: (
        <InputWithCheckbox
          label={getTranslation(
            parentOptions[productOptionKeys.HYDRAULIC_TRAY_SLOPE]
          )}
          name={productOptionKeys.HYDRAULIC_TRAY_SLOPE}
          checkboxValue={
            this.props[productOptionKeys.HYDRAULIC_TRAY_SLOPE_CHECKBOX]
          }
          onInputBlur={() => {
            this.calculateHeightMaxFromSlopeHeightMinLength();
            this.calculateHeightMinFromSlopeHeightMaxLength();
          }}
          inputHint={`(measured in {${
            parentOptions[productOptionKeys.HYDRAULIC_TRAY_SLOPE][
              'measurement_unit'
            ]
          })}`}
          validate={[
            number,
            required,
            numberMultiplicity05,
            traySlopeAndTrayHeight
          ]}
          format={this.formatNumberValue}
          instruction="Make sure you specify the length of the channel and fill in one of three fields:<br>Length, H min, H max"
        />
      ),
      [productOptionKeys.HYDRAULIC_HEIGHT_MIN]: (
        <InputWithCheckbox
          label="H min"
          name={productOptionKeys.HYDRAULIC_HEIGHT_MIN}
          onInputBlur={() => {
            this.calculateHeightMaxFromHeightMinAndLength();
            this.calculateTraySlopeFromHeightAndLength();
            this.calculateHeightMaxFromSlopeHeightMinLength();
          }}
          checkboxValue={
            this.props[productOptionKeys.HYDRAULIC_HEIGHT_MIN_CHECKBOX]
          }
          inputHint={`(measured in {${defaultLengthUnit}})`}
          validate={[number, required, valueBetweenRange]}
          format={this.formatNumberValue}
          instruction="Make sure you specify the length of the channel and fill in one of three fields:<br>Length, H min, H max"
        />
      ),
      [productOptionKeys.HYDRAULIC_HEIGHT_MAX]: (
        <InputWithCheckbox
          label="H max"
          name={productOptionKeys.HYDRAULIC_HEIGHT_MAX}
          checkboxValue={
            this.props[productOptionKeys.HYDRAULIC_HEIGHT_MAX_CHECKBOX]
          }
          onInputBlur={() => {
            this.calculateTraySlopeFromHeightAndLength();
            this.calculateHeightMinFromSlopeHeightMaxLength();
          }}
          inputHint={`(measured in {${defaultLengthUnit}})`}
          validate={[number, required, valueBetweenRange]}
          format={this.formatNumberValue}
          instruction="Make sure you specify the length of the channel and fill in one of three fields:<br>Length, H min, H max"
        />
      ),
      [productOptionKeys.HYDRAULIC_CONNECTION_TYPE]: (
        <HydraulicConnectionTypeDropdown {...this.props} />
      ),
      [productOptionKeys.HYDRAULIC_DRAINAGE_TYPE]: (
        <HydraulicDrainageTypeDropdown
          {...this.props}
          onChange={this.filterOptionsOnNoDreinaging()}
        />
      ),
      [productOptionKeys.HYDRAULIC_WATER_SEAL]: !this.isNoDreinagingSelected() ? (
        <Checkbox
          label={getTranslation(
            parentOptions[productOptionKeys.HYDRAULIC_WATER_SEAL]
          )}
          name={productOptionKeys.HYDRAULIC_WATER_SEAL}
        />
      ) : CONFIG.STATE.INVISIBLE,
      [productOptionKeys.HYDRAULIC_WATER_SEAL_AND_CATCHER]: !this.isNoDreinagingSelected() ? (
        <Checkbox
          label={getTranslation(
            parentOptions[productOptionKeys.HYDRAULIC_WATER_SEAL_AND_CATCHER]
          )}
          name={productOptionKeys.HYDRAULIC_WATER_SEAL_AND_CATCHER}
          validate={[waterSealCheckboxes]}
        />
      ) : CONFIG.STATE.INVISIBLE,
      [productOptionKeys.NO_END_LID_IN_BEGINNING_OF_LINE]: !this.isNoDreinagingSelected() ? (
        <Checkbox
          label={getTranslation(
            parentOptions[productOptionKeys.NO_END_LID_IN_BEGINNING_OF_LINE]
          )}
          name={productOptionKeys.NO_END_LID_IN_BEGINNING_OF_LINE}
        />
      ) : CONFIG.STATE.INVISIBLE,
      [productOptionKeys.HYDRAULIC_RELEASE_DIRECTION]: !this.isNoDreinagingSelected() ? (
        <HydraulicReleaseDirectionDropdown {...this.props} />
      ) : CONFIG.STATE.INVISIBLE,
      [productOptionKeys.OUTFALL_DIAMETR]: !this.isNoDreinagingSelected() ? (
        <OutfallDiametrDropdown {...this.props} />
      ) : CONFIG.STATE.INVISIBLE
    };

    return staticComponents[key] || false;
  };

  getOptionsSequenceAsArray = (options) => {
    const sequence = [];
    let currentOption = options.find(option => (
      !option.previous_option_id && option.product_id === CONFIG.PRODUCT_IDS.INTERNAL_DRAINAGE
    ))

    if (!currentOption) {
      return [];
    }

    sequence.push(currentOption);

    options.forEach(element => {
      const nextOption = options.find(option => (
        option.previous_option_id == currentOption.id
      ));

      if (!nextOption) {
        return;
      }

      currentOption = nextOption;
      sequence.push(nextOption);
    })

    return sequence;
  };

  render() {
    const {
      products: {
        [internalDrainageKey]: { parentOptions }
      },
      user: {
        measurementSystem: { defaultLengthUnit }
      },
      getTranslation
    } = this.props;
    const optionsSequence = this.state.options;

    return (
      <div>
        {optionsSequence &&
          optionsSequence.map(item => {
            const staticComponent = this.getSystemComponent(item.option_key, this.props);
            if (staticComponent == CONFIG.STATE.INVISIBLE) {
              return false;
            }

            if (staticComponent) {
              return (
                <div key={item.id}>
                  {this.getSystemComponent(item.option_key, this.props)}
                </div>
              )
            }

            return <DynamicField
              key={item.id}
              item={item}
              {...this.props}
            />
          })}
      </div>
    );
  }
}

export default InternalDrainageParams;

