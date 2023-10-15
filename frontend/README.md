# ReactJS frontend

We mostly use ReactJS for building complex forms.

Forms built with ReactJS:
* Order form
* Order list

------------

*Working directory is `frontend`*

Installation
------------
Intall dependencies
```
npm install
```

Development
------------
Build js files once
```
npm run build
```

Build files and watching for changes
```
npm run watch
```

Run eslint
```
npm run lint
```

Run prettier
```
npm run prettier
```

Testing
------------
Run unit tests
```
npm run test
```

Tests for components we place along with components but adding `.test.` suffix:
```
OrderForm.js
OrderForm.test.jsx
```

For unit tests we use:
* jest
* enzyme

### Resetting browser cache
Every time webpack builds a new version of react pages it generates `COMMITHASH` files during build based on a local git repository.

### Tracking errors
`Bugsnag` is being used to track errors only on beta/production environment

### Tracking user interactions
`Fullstory` is being used to track user interactions
