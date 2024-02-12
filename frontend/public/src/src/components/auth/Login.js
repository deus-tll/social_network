import {useEffect, useRef, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {useDispatch} from 'react-redux';
import {Button, Form} from 'react-bootstrap';
import {useLoginMutation} from '../../services/auth/authApiSliceService';
import myLog from "../../helpers/myLog";
import {setCredentials} from "../../services/auth/authSliceService";

const Login = () => {
  const emailRef = useRef()
  const errorRef = useRef()
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const [errMsg, setErrorMsg] = useState('')
  const navigate = useNavigate()

  const [login, { isLoading }] = useLoginMutation()
  const dispatch = useDispatch()

  useEffect(() => {
    emailRef?.current?.focus();
  }, [])

  useEffect(() => {
    setErrorMsg('');
  }, [email, password])

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
      if (!error?.originalStatus) {
        setErrorMsg('No Server Response');
      } else if (error.originalStatus === 401) {
        setErrorMsg('Unauthorized');
      } else {
        setErrorMsg('Login Failed');
      }

      myLog('Login', 'handleSubmit', `error ${error}`);

      errorRef?.current?.focus();
    }
  };

  const handleEmailInput = (e) => setEmail(e.target.value);

  const handlePasswordInput = (e) => setPassword(e.target.value);


  return isLoading ? <h1>Loading...</h1> : (
    <section className="login">
      <p ref={errorRef} className={errMsg ? "errmsg" : "offscreen"} aria-live="assertive">{errMsg}</p>

      <h1>Login</h1>

      <Form onSubmit={handleSubmit}>
        <Form.Group controlId="formBasicEmail">
          <Form.Label>Електронна пошта</Form.Label>
          <Form.Control
            type="email"
            placeholder="Введіть електронну пошту"
            ref={emailRef}
            value={email}
            onChange={handleEmailInput}
            required
          />
        </Form.Group>

        <Form.Group controlId="formBasicPassword">
          <Form.Label>Пароль</Form.Label>
          <Form.Control
            type="password"
            placeholder="Пароль"
            value={password}
            onChange={handlePasswordInput}
            required
          />
        </Form.Group>

        <Button variant="primary" type="submit">
          Увійти
        </Button>
      </Form>
    </section>
  );
};

export default Login;