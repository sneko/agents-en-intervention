export type RpcResponse<Data> = RpcSuccessResponse<Data> | RpcErrorResponse;

export type RpcSuccessResponse<Data> = Data;

export type RpcErrorResponse = {
  url: string;
  statusCode: number;
  statusMessage: string;
  message: string;
  stack: string;
  data: {
    code: number;
    message: string;
  };
};

// According to OpenAPI specification
// https://swagger.io/specification/
export const jsonRpcSuccessResponse = (data: unknown) => {
  return data;
};

export const jsonRpcErrorResponse = (err: Error) => {
  return {
    url: 'TODO',
    statusCode: -50100,
    statusMessage: 'Custom error code since local mock',
    message: err.message,
    stack: err.stack,
    data: {
      code: -50100,
      message: err.message,
    },
  };
};
