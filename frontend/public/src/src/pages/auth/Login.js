import { useEffect, useRef, useState } from 'react';
import {Link, useNavigate} from 'react-router-dom';
import { useDispatch } from 'react-redux';
import { Alert, Button, Form } from 'react-bootstrap';
import { useLoginMutation } from '../../services/auth/authApiSliceService';
import myLog from '../../helpers/myLog';
import { setCredentials } from '../../services/auth/authSliceService';
import AuthWrapper from '../../components/auth/AuthWrapper';

const Login = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();

  const emailRef = useRef();
  const errorRef = useRef();
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });
  const [rememberMe, setRememberMe] = useState(false);
  const [errors, setErrors] = useState({});

  const [login, { isLoading }] = useLoginMutation();

  useEffect(() => {
    emailRef?.current?.focus();
  }, []);

  useEffect(() => {
    setErrors({});
  }, [formData.email, formData.password]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const result = await login(formData).unwrap();
      const access_token = result.data.authorization.access_token;
      const user = result.data.user;

      dispatch(setCredentials({ user, accessToken: access_token, rememberMe }));

      navigate('/welcome');
    } catch (error) {
      if (error.originalStatus) {
        setErrors({general: error.error});
      } else if(error.status === 422) {
        setErrors(error.data.errors);
      } else if (error.status === 401 || 500) {
        setErrors({ general: `${error?.data?.status}: ${error?.data?.message}. ${error?.data?.error}` });
      } else {
        setErrors({ general: 'Login Failed' });
      }

      myLog('Login', 'handleSubmit', `error - ${JSON.stringify(error)}`);

      errorRef?.current?.focus();
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  return (
    <AuthWrapper>
      <section>
        {isLoading ? (
          <h1>Loading...</h1>
        ) : (
          <div>
            <Form onSubmit={handleSubmit}>
              <h3>Sign In</h3>

              <Form.Group className="mb-3" controlId="email">
                <Form.Label>
                  Email address
                  <Form.Control
                    type="email"
                    placeholder="Enter email"
                    ref={emailRef}
                    value={formData.email}
                    onChange={handleChange}
                    name="email"
                    isInvalid={errors.hasOwnProperty('email')}
                    onKeyDown={async (e) => {
                      if (e.key === 'Enter') {
                        await handleSubmit(e);
                      }
                    }}
                    required/>
                </Form.Label>

                <Form.Control.Feedback type="invalid">
                  {errors && errors.hasOwnProperty('email') && errors.email[0]}
                </Form.Control.Feedback>
              </Form.Group>

              <Form.Group className="mb-3" controlId="password">
                <Form.Label>
                  Password
                  <Form.Control
                    type="password"
                    placeholder="Enter password"
                    value={formData.password}
                    onChange={handleChange}
                    name="password"
                    isInvalid={errors.hasOwnProperty('password')}
                    onKeyDown={async (e) => {
                      if (e.key === 'Enter') {
                        await handleSubmit(e);
                      }
                    }}
                    required/>
                </Form.Label>

                <Form.Control.Feedback type="invalid">
                  {errors && errors.hasOwnProperty('password') && errors.password[0]}
                </Form.Control.Feedback>
              </Form.Group>

              <div className="mb-3">
                <div className="custom-control custom-checkbox">
                  <label className="custom-control-label" htmlFor="customCheck1">
                    <input
                      type="checkbox"
                      className="custom-control-input"
                      id="customCheck1"
                      checked={rememberMe}
                      onChange={(e) => setRememberMe(e.target.checked)}/>
                    Remember me
                  </label>
                </div>
              </div>

              <div className="d-grid">
                <Button type="submit" variant="primary">
                Submit
                </Button>
              </div>

              <div className="d-flex align-items-center justify-content-between">
                <p className="forgot-password text-right">
                  Forgot <a href="#">password?</a>
                </p>

                <p className="forgot-password text-right">
                  Don't have an account yet <Link to="/register">sign up?</Link>
                </p>
              </div>
            </Form>

            {errors && errors.hasOwnProperty('general') && <Alert variant="danger">{errors.general}</Alert>}
          </div>
        )}
      </section>
    </AuthWrapper>
  );
};

export default Login;