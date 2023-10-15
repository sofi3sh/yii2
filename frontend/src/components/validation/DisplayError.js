import React from 'react';

import Translate from '../Translate';

const DisplayError = ({ metaData: { error } }) => {
  return (
    <div>
      {error && (
        <div className="alert alert-danger p-2 mt-1 mb-0">
          <Translate>{error}</Translate>
        </div>
      )}
    </div>
  );
};

export default DisplayError;
