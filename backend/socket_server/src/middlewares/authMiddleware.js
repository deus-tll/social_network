import jwt from 'jsonwebtoken';

const JWT_SECRET = 'MvEhcnr5vgFWXB40Qed9tzouSLHNnRYYY7EbSg8dFkTW8s0kpgQ7JeTQxlzliaYu';
const JWT_ALGO = 'HS256';

const authMiddleware = (socket, next) => {
  const token = socket.handshake.auth.token;
  console.debug('(authMiddleware) token: ' + token);

  try {
    const decodedToken = jwt.verify(token, JWT_SECRET, { algorithms: [JWT_ALGO] });
    console.debug('(authMiddleware) decodedToken: ' + decodedToken);

    const userId = decodedToken.sub;
    socket.join('userId_' + userId);
  }
  catch (error) {
    console.error('Error while decoding token:', error.message);
  }

  next();
};

export default authMiddleware;