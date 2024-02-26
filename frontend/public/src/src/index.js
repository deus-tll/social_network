import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import App from './App';

import {store} from "./redux/store";
import {Provider} from "react-redux";
import {BrowserRouter, Routes, Route} from "react-router-dom";
import {SocketProvider} from "./providers/socket/SocketProvider";
import AuthProvider from "./providers/auth/AuthProvider";

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <Provider store={store}>
      <AuthProvider>
        <SocketProvider>
          <BrowserRouter>
            <Routes>
              <Route path="/*" element={<App />}/>
            </Routes>
          </BrowserRouter>
        </SocketProvider>
      </AuthProvider>
    </Provider>
  </React.StrictMode>
);