import React from 'react';
import Translate, { manageTranslation } from '../Translate';
import withData from '../../hocs/withData';

const Dropdown = ({
  label,
  name,
  options,
  onChange,
  placeholder = 'Select one',
  translations
}) => {
  return (
    <div>
      <div className="col-10 col-form-label">
        <label htmlFor={name}>
          <Translate>{label}</Translate>
        </label>
      </div>
        <div>
        <div>
          <select className="form-control" name={name} onChange={onChange}>
            <option value="">
              {manageTranslation(placeholder, translations)}
            </option>
            {options &&
              options.map(option => (
                <option value={option.value} key={option.value}>
                  {option.label}
                </option>
              ))}
          </select>
        </div>
      </div>
    </div>
  );
};

export default withData(['translations'])(Dropdown);
