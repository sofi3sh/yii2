import React from 'react';
import { Field } from 'redux-form';
import Translate from '../Translate';
import DisplayError from '../validation/DisplayError';

const renderDropdownField = ({ input, meta, children, disabled }) => (
  <div>
    <select {...input} className="form-control" disabled={disabled}>
      {children}
    </select>
    <DisplayError metaData={meta} />
  </div>
);

const Dropdown = ({
  label,
  name,
  options,
  onChange,
  defaultEmpty,
  validate,
  disabled
}) => {
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
          component={renderDropdownField}
          onChange={onChange}
          validate={validate}
          disabled={disabled}
        >
          {defaultEmpty && <option value="" disabled />}
          {options.map(option => {
            return (
              <option value={option.value} key={option.value}>
                {option.label}
              </option>
            );
          })}
        </Field>
      </div>
    </div>
  );
};

export default Dropdown;
