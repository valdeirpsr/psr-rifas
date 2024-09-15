<p align="center"><a href="https://valdeir.dev" target="_blank"><img src="https://svgshare.com/i/vqm.svg" width="400" alt="Logo do Projeto"></a></p>

<p align="center">
<a href="https://github.com/valdeirpsr/psr-rifas/blob/main/LICENSE"><img src="https://img.shields.io/github/license/valdeirpsr/psr-rifas?logo=github&color=green&label=License" alt="License" /></a>
<a href="https://github.com/valdeirpsr/psr-rifas/actions/workflows/tests.yml"><img src="https://github.com/valdeirpsr/psr-rifas/actions/workflows/tests.yml/badge.svg" alt="Actions tests with vitest and PHPUnit" /></a>
<a href="https://github.com/valdeirpsr/psr-rifas/actions/workflows/check-codes.yml"><img src="https://github.com/valdeirpsr/psr-rifas/actions/workflows/check-codes.yml/badge.svg" alt="Actions Check Code" /></a>
<a href="https://twitter.com/valdeirpsr"><img src="https://img.shields.io/twitter/follow/valdeirpsr" alt="Follow me on Twitter: valdeirpsr" /></a>
</p>

# PSRifas

Hoje eu quero compartilhar com vocês um projeto de estudo incrível que eu desenvolvi: um sistema de rifa digital, que permite que você crie e gerencie suas próprias rifas online.

Com esse sistema, você pode escolher sua forma de pagamento preferida, criar rifas personalizadas, definir o ganhador e ter uma página exclusiva para vender seus bilhetes.

É muito fácil, rápido e divertido! Você pode usar esse sistema para arrecadar dinheiro para uma causa nobre, para realizar um sonho, para estudar o funcionamento do projeto ou simplesmente para se divertir com seus amigos. O sistema é seguro, confiável e transparente. Você pode acompanhar todas as informações das suas rifas em tempo real e receber o pagamento diretamente na sua conta.


## Demonstração

<table border="0">
<tr>
<td><b>Comprando bilhetes</b></td>
<td><b>Definindo bilhete premiado</b></td>
</tr>
<tr>
<td><a href="https://youtube.com/shorts/DoeD1DB_Jo8" target="_blank"><img src="https://img.youtube.com/vi/DoeD1DB_Jo8/hqdefault.jpg" alt="Vídeo de demonstração - Parte 1" /></a></td>
<td><a href="https://youtube.com/shorts/GmjgG-M2XDI" target="_blank"><img src="https://img.youtube.com/vi/GmjgG-M2XDI/hqdefault.jpg" alt="Vídeo de demonstração - Parte 2" /></a></td>
</tr>
</table>


## Variáveis de Ambiente

Para rodar esse projeto, você vai precisar adicionar as seguintes variáveis de ambiente no seu .env

```
# Prazo de pagamento
# Caso o prazo seja atingido e o pagamento não seja feito,
# o pedido será removido e os números liberados
RIFA_EXPIRE_AT_MINUTES=60
```

```
# Access Token do MercadoPago para gerenciar seu pagamento
MERCADOPAGO_ACCESS_TOKEN=<string>
```


## Rodando localmente

Clone o projeto

```bash
  git clone https://github.com/valdeirpsr/psr-rifas.git
```

Entre no diretório do projeto

```bash
  cd psr-rifas
```

Instale as dependências do PHP

```bash
  composer require
```

Crie a estrutura do banco de dados

```bash
  php artisan migrate

  # Com dados fakes (opcional)
  php artisan migrate --seed
```

Instale as dependências do javascript

```bash
  pnpm i
```

Inicie o servidor do PHP

```bash
  php artisan serve
```

Inicie vite

```bash
  pnpm dev
```
## Rodando localmente com Docker Compose

Clone o projeto

```bash
  git clone https://github.com/valdeirpsr/psr-rifas.git
```

Entre no diretório do projeto

```bash
  cd psr-rifas
```

Execute o comando abaixo no seu terminal

```bash
  docker run --rm --volume "$PWD:/app" composer require --ignore-platform-reqs;
  docker compose up -d;
```

Caso seja necessário, acesse o container `laravel.test` e execute o vite

```bash
  docker-compose exec laravel.test sh -c "pnpm dev"
```

## Rodando os testes

Para rodar os testes do *JavaScript*, execute o seguinte comando

```bash
  npx vitest
```

Para rodar os testes do *Laravel*, execute o seguinte comando

```bash
  php artisan test
```
## Deploy

Leia [Deployment com Laravel](https://laravel.com/docs/10.x/deployment)

## Stack utilizada

**Front-end:** Vue 3, Vite, Typescript, InertiaJs, TailwindCSS

**Back-end:** PHP 8.2, Laravel 10.10.x

## Roadmap

- [ ] Utilizar Repositories para realizar busca através dos Models

- [ ] Integrar o sistema com o mecanismo de busca Meilisearch

- [x] Criar uma página para visualização das informações de rifa no painel de controle: número de bilhetes vendidos, gráfico com vendas por data, ranking dos compradores. <sup>Nota 1</sup>

- [ ] Adicionar suporte para desconto por quantidade

- [ ] Adicionar agendamento para publicação das rifas

- [ ] Adicionar outras formas de pagamento

- [ ] Adicionar mecanismo de  análise de código PHP

## Aprendizados

Durante a construção deste projeto, aprendi muito sobre a estrutura e o funcionamento do Laravel, que é um framework PHP poderoso e flexível para o desenvolvimento web.
Entendi melhor alguns padrões que podem ser usados no Laravel e o funcionamento de alguns deles.

Além disso, aprofundei meus conhecimentos sobre testes no Laravel, explorando diversas abordagens para garantir a qualidade do código e a estabilidade do projeto. Tanto testes de integração quanto testes diretamente no models. Ainda não testei o PHPest, mas não fará oportunidade para conhecê-lo e usá-lo.

Um dos maiores desafios que enfrentei foi entender completamente o funcionamento do Eloquent ORM, mas foi resolvido com o fácil suporte em queries complexas.

Outro desafio que encontrei foi a integração direta do Laravel com o Vue.js. No início, cogitei transformar o Laravel em um sistema de API e usar requisições no Vue 3 para exibir os dados para o usuário. No entanto, fui capaz de superar esse obstáculo com o uso do pacote Inertia.js.

Ademais, o Filament me ajudou a criar toda estrutura do painel de controle. Foi uma oportunidade de aprender como integrar soluções externas ao projeto, aproveitando suas funcionalidades para economizar tempo e esforço no desenvolvimento.

No geral, o processo de construção deste projeto foi muito enriquecedor. Conheci algumas limitações e pude aprender a superá-las. Ao final, sinto que ganhei um conhecimento valioso sobre o ecossistema Laravel e sua integração com tecnologias.
