import AuthWrapper from "./AuthWrapper";
import {useRegisterMutation} from "../../services/auth/authApiSliceService";
import {useDispatch} from "react-redux";
import {Link, useNavigate} from "react-router-dom";
import {useEffect, useRef, useState} from "react";
import {Alert, Form} from "react-bootstrap";
import {setCredentials} from "../../services/auth/authSliceService";

const Register = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();

  const emailRef = useRef();
  const errorRef = useRef();
  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [email, setEmail] = useState('');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [errorMessage, setErrorMessage] = useState('');

  const [register, { isLoading }] = useRegisterMutation();

  useEffect(() => {
    emailRef?.current?.focus();
  }, []);

  useEffect(() => {
    setErrorMessage('');
  }, [email, username, password]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const result = await register({
        name: `${firstName} ${lastName}`,
        email: email,
        username: username,
        password: password,
        password_confirmation: passwordConfirmation
      }).unwrap();

      const accessToken = result.data.authorization.accessToken;
      const user = result.data.user;

      dispatch(setCredentials({ user: user, accessToken: accessToken }));

      setFirstName('');
      setLastName('');
      setEmail('');
      setUsername('');
      setPassword('');
      setPasswordConfirmation('');
    }
    catch (error) {

    }
  };

  const handleFirstNameInput = (e) => setFirstName(e.target.value);
  const handleLastNameInput = (e) => setLastName(e.target.value);
  const handleEmailInput = (e) => setEmail(e.target.value);
  const handleUsernameInput = (e) => setUsername(e.target.value);
  const handlePasswordInput = (e) => setPassword(e.target.value);
  const handlePasswordConfirmationInput = (e) => setPasswordConfirmation(e.target.value);

  return(
    <AuthWrapper>
      <section>
        {isLoading ? (
          <h1>Loading...</h1>
        ) : (
          <div>
            <Form onSubmit={handleSubmit}>
              <h3>Sign Up</h3>
              <div className="mb-3">
                <label>First name</label>
                <input
                  type="text"
                  className="form-control"
                  placeholder="John"
                  value={firstName}
                  onChange={handleFirstNameInput}
                  required
                />
              </div>
              <div className="mb-3">
                <label>Last name</label>
                <input
                  type="text"
                  className="form-control"
                  placeholder="Doe"
                  value={lastName}
                  onChange={handleLastNameInput}
                  required
                />
              </div>
              <div className="mb-3">
                <label>Username</label>
                <input
                  type="text"
                  className="form-control"
                  placeholder="john_doe123"
                  value={email}
                  onChange={handleUsernameInput}
                  required
                />
              </div>
              <div className="mb-3">
                <label>Email address</label>
                <input
                  type="email"
                  className="form-control"
                  placeholder="johndoe@gmail.com"
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
                <label>Password Confirmation</label>
                <input
                  type="password"
                  className="form-control"
                  placeholder="Enter password again"
                  value={passwordConfirmation}
                  onChange={handlePasswordConfirmationInput}
                  required
                />
              </div>
              <div className="d-grid">
                <button type="submit" className="btn btn-primary">
                  Sign Up
                </button>
              </div>
              <p className="forgot-password text-right">
                Already registered <Link to="/login">sign in?</Link>
              </p>
            </Form>

            {errorMessage && <Alert variant="danger">{errorMessage}</Alert>}
          </div>
        )}
      </section>
    </AuthWrapper>
  );
};

export default Register;