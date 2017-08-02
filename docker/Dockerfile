FROM composer as build-env
RUN mkdir -p /app
WORKDIR /app
ADD composer.* /app/
ADD tests /app/tests
ADD database /app/database
RUN composer install

FROM alpine
RUN mkdir -p /app
RUN apk update \
    && apk --no-cache upgrade
WORKDIR /app
ADD . .
COPY --from=build-env /app /app
ARG BUILD
RUN echo "Build versions: "$BUILD > /app/versions
RUN chow -R 100:100 /app/
VOLUME ["/app"]
