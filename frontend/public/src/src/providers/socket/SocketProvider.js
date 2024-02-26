import {createContext, useContext} from 'react';
import useSocketConnection from "./useSocketConnection";

const SocketContext = createContext();

export const useSocket = () => useContext(SocketContext);

export const SocketProvider = ({ children }) => {
  const socketConnection = useSocketConnection();

  return (
    <SocketContext.Provider value={{ socketConnection }}>
      {children}
    </SocketContext.Provider>
  );
};