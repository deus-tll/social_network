import {setUserAvatars} from "../../services/auth/authSliceService";

export const handleConnect = () => {
  console.log('Connected to socket server');
};

export const handleDisconnect = () => {
  console.log('Disconnected from socket server');
};

export const handleMyNameIs = (data) => {
  console.log('Connect to server: ' + data);
};

export const handlePing = (data) => {
  console.log('Ping from server:', data);
};

export const handleAvatarsStored = (data) => {
  const avatars = JSON.parse(data);
  console.log('Received avatars:', avatars);

  //тут я розумію що воно не спрацює, бо потрібно через dispatch викликати, але воно в цілому поки не вдається вивести в консоль Received avatars хоча б.
  setUserAvatars({avatars});
};