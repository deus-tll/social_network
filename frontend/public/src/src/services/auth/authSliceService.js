import {createSlice} from "@reduxjs/toolkit";

const authSliceService = createSlice({
  name: 'auth',
  initialState: { user: null, token: null },
  reducers: {
    setCredentials: (state, action) => {
      const { user, accessToken, rememberMe } = action.payload;

      state.user = user;
      state.token = accessToken;

      if (rememberMe) {
        localStorage.setItem('accessToken', accessToken);
        localStorage.setItem('user', JSON.stringify(user));
      } else {
        localStorage.removeItem('accessToken');
        localStorage.removeItem('user');
      }
    },
    logOut: (state, action) => {
      state.user = null;
      state.token = null;

      localStorage.removeItem('accessToken');
      localStorage.removeItem('user');
    },
    setUserAvatars: (state, action) => {
      const { avatars } = action.payload;

      state.user.avatars = avatars;

      const storedUser = JSON.parse(localStorage.getItem('user'));
      storedUser.avatars = avatars;
      localStorage.setItem('user', JSON.stringify(storedUser));
    }
  },
});

export const { setCredentials, logOut, setUserAvatars } = authSliceService.actions;

export default authSliceService.reducer;

export const selectCurrentUser = (state) => state.auth.user;
export const selectCurrentToken = (state) => state.auth.token;