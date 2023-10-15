import React from 'react';
import { Field } from 'redux-form';
import DisplayError from '../validation/DisplayError';
import Translate from '../Translate';

const renderCheckboxField = ({ input, type, meta }) => (
  <div>
    <input {...input} type={type} />
    <DisplayError metaData={meta} />
  </div>
);

const Checkbox = ({ label, name, validate }) => {
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
          component={renderCheckboxField}
          type="checkbox"
          validate={validate}
        />
      </div>
    </div>
  );
};

export default Checkbox;
