import { apiSliceService } from "../api/apiSliceService";

export const authApiSliceService = apiSliceService.injectEndpoints({
  endpoints: builder => ({
    login: builder.mutation({
      query: credentials => ({
        url: '/auth/login',
        method: 'POST',
        body: { ...credentials }
      })
    }),
    register: builder.mutation({
      query: credentials => ({
        url: '/auth/register',
        method: 'POST',
        body: { ...credentials }
      })
    }),
  })
});

export const {
  useLoginMutation,
  useRegisterMutation
} = authApiSliceService