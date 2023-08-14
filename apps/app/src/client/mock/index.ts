import { DefaultBodyType, DelayMode, PathParams, ResponseTransformer, RestRequest, rest } from 'msw';

import { QueryOperation } from '@aei/app/src/client/generated/components';
import { fetch } from '@aei/app/src/client/generated/fetcher';
import { mockBaseUrl } from '@aei/app/src/client/mock/environment';
import { jsonRpcErrorResponse, jsonRpcSuccessResponse } from '@aei/app/src/client/mock/requests';

// TODO: improve typings once the generator gives us more flexibility (ref: https://github.com/fabien0102/openapi-codegen/issues/193)

type GenericOperationFetch = (variables: any, signal?: AbortSignal) => Promise<any>;

type FirstParamType<F> = F extends (arg1: infer A, ...args: any[]) => any ? A : never;

type ExtractPromiseType<T> = T extends Promise<infer U> ? U : never;
type ReturnTypeOfFunction<T> = T extends (...args: any[]) => infer R ? R : never;

/**
 * Mocks an OpenAPI endpoint and returns a msw handler for Storybook.
 * The operationId and response is fully typed and infers the type from your routes file.
 * @todo make it accept multiple endpoints
 * @param endpoint.operationId - operationId to the endpoint ex. ["post", "create"]
 * @param endpoint.response - response to return ex. {id: 1}
 * @param endpoint.type - specific type of the endpoint ex. "query" or "mutation" (defaults to "query")
 * @returns - msw endpoint
 * @example
 * Page.parameters = {
    msw: {
      handlers: [
        getApiMock<typeof fetchApiExamplesGetCollection>({
          path: "/examples"
          method: "get",
          response: [
            { id: 0, title: "test" },
            { id: 1, title: "test" },
          ],
        }),
      ],
    },
  };
 */
export const getApiMock = <OperationFetch extends GenericOperationFetch>(endpoint: {
  path: QueryOperation['path']; // Cast as `any` for mutations, waiting for the library to provide those types
  response: ExtractPromiseType<ReturnTypeOfFunction<OperationFetch>>;
  method?: keyof typeof rest;
  delayHook?: (req: RestRequest<DefaultBodyType, PathParams<string>>, params: FirstParamType<OperationFetch>) => number | DelayMode | null;
}) => {
  const fn = endpoint.method ? rest[endpoint.method] : rest.get;

  const compatiblePath = endpoint.path.replace(/{(\w+)}/g, ':$1'); // MSW has its own format for path parameters

  const route = `${mockBaseUrl}${compatiblePath}`;

  return fn(route, (req, res, ctx) => {
    const isResponseAnError = (endpoint.response as any) instanceof Error;

    let rpcResponse: DefaultBodyType;
    if (isResponseAnError) {
      rpcResponse = jsonRpcErrorResponse(endpoint.response as Error);
    } else {
      rpcResponse = jsonRpcSuccessResponse(endpoint.response);
    }

    const transformers: ResponseTransformer<DefaultBodyType, any>[] = [];

    if (!!endpoint.delayHook) {
      let params = req.params as FirstParamType<OperationFetch>;

      const delayToAdd = endpoint.delayHook(req, params);

      if (delayToAdd !== null && delayToAdd !== 0) {
        transformers.push(ctx.delay(delayToAdd));
      }
    }

    transformers.push(ctx.json(rpcResponse));

    return res(...transformers);
  });
};
