import React from 'react';
import { Field } from 'redux-form';

import { renderInputField } from './Input';
import Translate from '../Translate';
import Instruction from '../Instruction';

const isReadOnly = ({ checkboxValue }) => !checkboxValue;

const InputWithCheckbox = ({
  label,
  name,
  nameCheckbox,
  type,
  onInputBlur,
  checkboxValue,
  validate,
  inputHint,
  format,
  instruction
}) => {
  return (
    <div className="row form-group">
      <div className="col-2 col-form-label">
        <Field
          name={nameCheckbox || `${name}_checkbox`}
          component="input"
          type="checkbox"
          className="mr-1"
        />
        <label htmlFor={name}>{label}</label>
        <Instruction message={instruction} />
        <div className="font-size-xs">
          <Translate>{inputHint}</Translate>
        </div>
      </div>
      <div className="col-10">
        <Field
          name={name}
          component="input"
          component={renderInputField}
          type={type}
          readOnly={isReadOnly({ checkboxValue })}
          onBlur={onInputBlur}
          validate={validate}
          format={format}
        />
      </div>
    </div>
  );
};

export default InputWithCheckbox;
