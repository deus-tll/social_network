export const handleConnect = () => {
  console.log('Connected to socket server');
};

export const handleDisconnect = () => {
  console.log('Disconnected from socket server');
};

export const handleAvatarsStored = (data) => {
  const avatars = JSON.parse(data);
  console.log('Received avatars:', avatars);

};