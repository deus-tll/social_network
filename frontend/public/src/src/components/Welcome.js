import {useSelector} from "react-redux";
import {Link} from "react-router-dom";
import {selectCurrentToken, selectCurrentUser} from "../services/auth/authSliceService";

const Welcome = () => {
  const user = useSelector(selectCurrentUser);
  const token = useSelector(selectCurrentToken);

  const welcome = user ? `Welcome ${JSON.stringify(user)}!` : 'Welcome!';
  const tokenAbbr = `${token.slice(0, 9)}...`;

  return (
    <section className="welcome">
      <h1>{welcome}</h1>
      <p>Token: {tokenAbbr}</p>
      <p><Link to="/userslist">Go to the Users List</Link></p>
    </section>
  )
}
export default Welcome