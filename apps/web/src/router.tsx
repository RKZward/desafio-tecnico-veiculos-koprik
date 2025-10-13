import { createBrowserRouter, Navigate } from "react-router-dom";
import App from "./App";
import Login from "@/pages/Login";
import Register from "@/pages/Register";
import Home from "@/pages/Home";
import VehiclesList from "@/pages/vehicles/List";
import VehiclesForm from "@/pages/vehicles/Form";
import VehiclesDetail from "@/pages/vehicles/Detail";
import Protected from "@/components/Protected";

export const router = createBrowserRouter([
  {
    element: <App />,
    children: [
      { path: "/", element: <Navigate to="/veiculos" replace /> },
      { path: "/login", element: <Login /> },
      { path: "/registrar", element: <Register /> },
      {
        element: <Protected />,
        children: [
          { path: "/home", element: <Home /> },
          { path: "/veiculos", element: <VehiclesList /> },
          { path: "/veiculos/novo", element: <VehiclesForm /> },
          { path: "/veiculos/:id", element: <VehiclesDetail /> },
          { path: "/veiculos/:id/editar", element: <VehiclesForm /> },
        ],
      },
      { path: "*", element: <Navigate to="/veiculos" replace /> },
    ],
  },
]);
