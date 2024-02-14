import {useSelector} from "react-redux";
import {selectCurrentToken, selectCurrentUser} from "../services/auth/authSliceService";

const Welcome = () => {
  const user = useSelector(selectCurrentUser);
  const token = useSelector(selectCurrentToken);

  const welcome = user ? `Welcome ${user.first_name} ${user.last_name}!` : 'Welcome!';
  const tokenAbbr = `${token.slice(0, 20)}...`;

  return (
    <section>
      <h1>{welcome}</h1>
      <p>Token: {tokenAbbr}</p>
    </section>
  )
}
export default Welcome;