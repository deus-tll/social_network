import {useEffect, useRef, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {useDispatch} from 'react-redux';
import {Alert, Button, Form} from 'react-bootstrap';
import {useLoginMutation} from '../../services/auth/authApiSliceService';
import myLog from "../../helpers/myLog";
import {setCredentials} from "../../services/auth/authSliceService";
import AuthWrapper from "./AuthWrapper";

const Login = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();

  const emailRef = useRef();
  const errorRef = useRef();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [errorMessage, setErrorMessage] = useState('');

  const [login, { isLoading }] = useLoginMutation();

  useEffect(() => {
    emailRef?.current?.focus();
  }, []);

  useEffect(() => {
    setErrorMessage('');
  }, [email, password]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const result = await login({email, password}).unwrap();
      const accessToken = result.data.authorization.accessToken;
      const user = result.data.user;

      dispatch(setCredentials({ user: user, accessToken: accessToken }));

      setEmail('');
      setPassword('');
      navigate('/welcome');
    }
    catch (error) {
      if (error.status === 401) {
        setErrorMessage(`${error.data.status}: ${error.data.message}`);
      } else {
        setErrorMessage('Login Failed');
      }

      myLog('Login', 'handleSubmit', `error - ${JSON.stringify(error)}\n\n ${error}`);

      errorRef?.current?.focus();
    }
  };

  const handleEmailInput = (e) => setEmail(e.target.value);

  const handlePasswordInput = (e) => setPassword(e.target.value);


  return (
    <AuthWrapper>
      <section>
        {isLoading ? (
          <h1>Loading...</h1>
        ) : (
          <div>
            <Form onSubmit={handleSubmit}>
              <h3>Sign In</h3>
              <div className="mb-3">
                <label>Email address</label>
                <input
                  type="email"
                  className="form-control"
                  placeholder="Enter email"
                  value={email}
                  onChange={handleEmailInput}
                  required
                />
              </div>
              <div className="mb-3">
                <label>Password</label>
                <input
                  type="password"
                  className="form-control"
                  placeholder="Enter password"
                  value={password}
                  onChange={handlePasswordInput}
                  required
                />
              </div>

              <div className="mb-3">
                <div className="custom-control custom-checkbox">
                  <input
                    type="checkbox"
                    className="custom-control-input"
                    id="customCheck1"
                  />
                  <label className="custom-control-label" htmlFor="customCheck1">
                    Remember me
                  </label>
                </div>
              </div>

              <div className="d-grid">
                <button type="submit" className="btn btn-primary">
                  Submit
                </button>
              </div>
              <p className="forgot-password text-right">
                Forgot <a href="#">password?</a>
              </p>
            </Form>

            {errorMessage && <Alert variant="danger">{errorMessage}</Alert>}
          </div>
        )}
      </section>
    </AuthWrapper>
  );
};

export default Login;