import React from 'react';
import { Field } from 'redux-form';
import Translate from '../Translate';
import DisplayError from '../validation/DisplayError';

export const renderInputField = ({ input, type, meta, readOnly }) => (
  <div>
    <input
      {...input}
      className="form-control"
      type={type}
      readOnly={readOnly}
    />
    <DisplayError metaData={meta} />
  </div>
);

export const InputWithoutMarkup = ({
  label,
  name,
  readOnly,
  type,
  onInputBlur,
  validate,
  format
}) => {
  return (
    <Field
      name={name}
      component={renderInputField}
      type={type || 'text'}
      readOnly={readOnly}
      onBlur={onInputBlur}
      validate={validate}
      format={format}
    />
  );
};

const Input = ({ label, name, readOnly, type, onInputBlur, validate }) => {
  return (
    <div className="row form-group">
      <div className="col-2 col-form-label">
        <label htmlFor={name}>
          <Translate>{label}</Translate>
        </label>
      </div>
      <div className="col-10">
        <Field
          name={name}
          component={renderInputField}
          type={type || 'text'}
          readOnly={readOnly}
          onBlur={onInputBlur}
          validate={validate}
        />
      </div>
    </div>
  );
};

export default Input;
