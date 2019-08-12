This is the expected execution flow for a single merge request:

1. New request generated
1. Configured request
1. Validated request
1. Started processing request
    1. Selected item to process request
    1. Selected item-merger for item
    1. Processed request on item
        1. Successful processed item, or
            * Goes to "Selected item to process request", or
            * Goes to "Processed all items" when all items are processed
        1. Failed processed item
            * Goes to "Processed request"
    1. Processed all items
1. Processed request
    1. Successfully processed merge request, or
    1. Failed processed merge request
