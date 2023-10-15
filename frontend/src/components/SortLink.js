import React from 'react';
import { Link, withRouter } from 'react-router-dom';

import Translate from './Translate';

const renderUrlWithSortParams = sortKey => {
  const urlParams = new URLSearchParams(window.location.search);
  const currentUrl = new URL(window.location.href);
  const reversedSortParam =
    urlParams.get('sort') && urlParams.get('sort').includes('-')
      ? sortKey
      : `-${sortKey}`;
  urlParams.set('sort', reversedSortParam);
  currentUrl.search = urlParams.toString();
  return currentUrl.search.toString();
};

const SortLink = ({ sortKey, title }) => {
  return (
    <Link to={renderUrlWithSortParams(sortKey)}>
      <Translate>{title}</Translate>
    </Link>
  );
};

export default withRouter(SortLink);
