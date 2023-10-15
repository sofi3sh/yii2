import React from 'react';
import Translate from '../Translate';

const Input = ({ label, name, readOnly, type, onChange }) => {
  return (
    <div>
      <div className="col-10 col-form-label">
        <label htmlFor={name}>
          <Translate>{label}</Translate>
        </label>
      </div>
      <div>
        <input
          className="form-control"
          type={type}
          name={name}
          readOnly={readOnly}
          onChange={onChange}
        />
      </div>
    </div>
  );
};

export default Input;
