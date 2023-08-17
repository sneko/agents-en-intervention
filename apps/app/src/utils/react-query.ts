import { UseQueryResult } from '@tanstack/react-query';

export class AggregatedQueries {
  queries: UseQueryResult<any, any>[] = [];

  constructor(...queries: UseQueryResult<any, any>[]) {
    this.queries = queries;
  }

  public get hasError(): boolean {
    return this.errors.length > 0;
  }

  public get errors() {
    return this.queries.filter((query) => !!query.error).map((query) => query.error);
  }

  public get refetchs() {
    return this.queries.map((query) => query.refetch);
  }

  public get isLoading(): boolean {
    return this.queries.filter((query) => query.isLoading).length > 0;
  }
}
