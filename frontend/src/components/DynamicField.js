import React, { Component } from 'react';
import CONFIG from '../config/app';

import { Field } from 'redux-form';
import InputFile from './reduxForm/InputFile';
import Translate from './Translate';

const fieldConfig = {
  [CONFIG.PRODUCT_OPTION_TYPES.INPUT]: {
      component: 'input',
      type: 'text',
      className: 'form-control'
  },
  [CONFIG.PRODUCT_OPTION_TYPES.CHECKBOX]: {
      component: 'input',
      type: 'checkbox'
  },
  [CONFIG.PRODUCT_OPTION_TYPES.DROPDOWN]: {
      component: 'select',
      className: 'form-control'
  }
}

const DynamicField = props => {
  const {
    item: { option_key, option_type, childrenOptions, id },
    getTranslation
  } = props;
  const optionTypes = CONFIG.PRODUCT_OPTION_TYPES;

  let fieldProps = {
    name: option_key
  };

  const staticFieldProps = fieldConfig[+option_type];

  if (staticFieldProps) {
    fieldProps = Object.assign(staticFieldProps, fieldProps);
  }

  if (+option_type === optionTypes.FILE) {
    return (
      <InputFile
        label={getTranslation(props.item)}
        name={option_key}
        existingFile={option_key}
      />
    );
  }

  let field = '';
  if (option_type != optionTypes.DROPDOWN) {
    field = <Field {...fieldProps} />;
  } else {
    field = (
      <Field {...fieldProps}>
        {childrenOptions.map(option => {
          return (
            <option value={option.value} key={option.value}>
              {getTranslation(option)}
            </option>
          );
        })}
      </Field>
    );
  }

  return (
    <div className="row form-group" key={id}>
      <div className="col-2 col-form-label">
        <label htmlFor={name}>
          <Translate>{getTranslation(props.item)}</Translate>
        </label>
      </div>
      <div className="col-10">{field}</div>
    </div>
  );
};

export default DynamicField;
