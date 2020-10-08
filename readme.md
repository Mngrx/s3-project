# Mexer no S3

# Tecnologias utilizadas

- Laravel
- Sqlite3
- AdminLTE como dashboard

# Para utilizar

Crie uma conta no sistema, logue e poderá utilizar as funcionalidades.

# Atualização

Foi adicionado um área para mexer com um repositório específico que só manterá fotos que contenham gatos.

Basicamente, ao receber uma foto (.jpg, .jpeg ou .png), o sistema aciona uma função Lambda da AWS (Serverless), que utilza-se da API AWS Rekognition, verificando se encontra algum gato na foto. Em caso afirmativo, a foto se mantém no bucket, caso contrário é eliminada.

Com essa nova atualização, o repositório passou a ser do tipo monorepo, e trouxe o código da função Lambda para a pasta "lambda".

# Agradecimentos

- Professor Isaac, por passar o desafio e apresentar onde encontrar as funções na documentação.
