import {useSelector} from "react-redux";
import {selectCurrentToken, selectCurrentUser} from "../services/auth/authSliceService";
import myLog from "../helpers/myLog";

const Welcome = () => {
  const user = useSelector(selectCurrentUser);
  const token = useSelector(selectCurrentToken);

  const welcome = user ? `Welcome ${user.name}!` : 'Welcome!';
  const tokenAbbr = `${token.slice(0, 9)}...`;

  myLog('Welcome', 'none', user);

  return (
    <section>
      <h1>{welcome}</h1>
      <p>Token: {tokenAbbr}</p>
    </section>
  )
}
export default Welcome;