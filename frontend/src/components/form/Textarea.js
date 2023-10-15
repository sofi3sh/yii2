import React from 'react';
import Translate from '../Translate';

const Textarea = ({ label, name, rows = '5', onChange }) => {
  return (
    <div>
      <div className="col-10 col-form-label">
        <label htmlFor={name}>
          <Translate>{label}</Translate>
        </label>
      </div>
      <div>
        <textarea
          className="form-control"
          name={name}
          rows={rows}
          onChange={onChange}
        ></textarea>
      </div>
    </div>
  );
};

export default Textarea;
