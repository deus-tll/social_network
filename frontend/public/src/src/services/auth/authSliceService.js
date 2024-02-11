import {createSlice} from "@reduxjs/toolkit";

const authSliceService = createSlice({
  name: 'auth',
  initialState: { user: null, token: null },
  reducers: {
    setCredentials: (state, action) => {
      const { user, accessToken } = action.payload;

      state.user = user;
      state.token = accessToken;

      localStorage.setItem('accessToken', accessToken);
      localStorage.setItem('user', JSON.stringify(user));
    },
    logOut: (state, action) => {
      state.user = null;
      state.token = null;
      
      localStorage.removeItem('accessToken');
      localStorage.removeItem('user');
    }
  },
});

export const { setCredentials, logOut } = authSliceService.actions;

export default authSliceService.reducer;

export const selectCurrentUser = (state) => state.auth.user;
export const selectCurrentToken = (state) => state.auth.token;