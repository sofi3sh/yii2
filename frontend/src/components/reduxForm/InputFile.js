import React from 'react';
import { Field } from 'redux-form';
import Translate from '../Translate';
import DisplayError from '../validation/DisplayError';

const UploadFile = ({
  input: { value: omitValue, ...inputProps },
  meta,
  ...props
}) => (
    <div>
      <input type="file" {...inputProps} {...props} />
      <DisplayError metaData={meta} />
    </div>
  );

const renderFileUrl = file => {
  if (!file) {
    return false;
  }

  return (
    <a
      href={`/file/view/${file.id}`}
      target="_blank"
      className="btn btn-primary mt-2"
    >
      <Translate>View File</Translate>: {file.full_origin_name}
    </a>
  );
};

const InputFile = ({ label, name, existingFile, validate }) => {
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
          component={UploadFile}
          type="file"
          className="form-control"
          validate={validate}
        />
        {renderFileUrl(existingFile)}
      </div>
    </div>
  );
};

export default InputFile;
