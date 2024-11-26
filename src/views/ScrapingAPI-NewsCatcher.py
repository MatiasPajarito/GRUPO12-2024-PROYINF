# ScrapingAPI-NewsCatcher.py
import requests
import json
import sys
import logging
from datetime import datetime, timedelta

# Configurar logging
logging.basicConfig(
    filename='newscatcher_api.log',
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

def search_news(keywords, months=1):
    """
    Realiza la búsqueda de noticias usando NewsCatcher API v2
    """
    API_KEY = "7m2um9vc5JcC3RItkT15p9aIExaGkVuXBLOKgyKDTfU"
    base_url = "https://api.newscatcherapi.com/v2/search"
    
    # Configurar fecha "from" basada en los meses hacia atrás
    from_date = datetime.now() - timedelta(days=30*months)
    from_date_str = from_date.strftime("%Y/%m/%d")
    
    headers = {
        "x-api-key": API_KEY
    }
    
    params = {
        "q": keywords.replace(",", " OR "),    # Convertir CSV a formato OR
        "lang": "es",                          # Español
        "page_size": 20,                       # Límite de resultados
        "sort_by": "relevancy",                # Ordenar por relevancia
        "from": from_date_str,                 # Fecha desde (corregido de from_ a from)
        "search_in": "title_summary",          # Buscar en título y resumen
        "page": 1,                             # Página inicial
        "enable_full_semantics": True          # Para mejor relevancia en la búsqueda
    }
    
    try:
        logging.info(f"Iniciando búsqueda con keywords: {keywords}")
        logging.info(f"Parámetros: {params}")
        
        response = requests.get(base_url, headers=headers, params=params)
        logging.info(f"Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            results = []
            
            articles_count = len(data.get('articles', []))
            logging.info(f"Artículos encontrados: {articles_count}")
            
            for article in data.get('articles', []):
                title = article.get('title', '').replace(',', ' ').replace('(', ' ').replace(')', ' ').strip()
                desc = article.get('excerpt', '').replace(',', ' ').replace('(', ' ').replace(')', ' ').strip()
                url = article.get('link', '').strip()
                
                if title and url:
                    result = f"({title},{desc},{url})"
                    results.append(result)
                    logging.info(f"Procesado artículo: {title[:50]}...")
            
            return {
                'status': 'success',
                'noticias': ','.join(results),
                'count': len(results)
            }
        else:
            error_msg = f"Error en la API: {response.status_code}"
            if response.text:
                error_msg += f" - {response.text}"
            logging.error(error_msg)
            return {
                'status': 'error',
                'message': error_msg,
                'noticias': ''
            }
            
    except Exception as e:
        error_msg = f"Error en la solicitud: {str(e)}"
        logging.error(error_msg)
        return {
            'status': 'error',
            'message': error_msg,
            'noticias': ''
        }

if __name__ == "__main__":
    try:
        if len(sys.argv) >= 3:
            keywords = sys.argv[1]
            months = int(sys.argv[2])
            logging.info(f"Iniciando script con keywords={keywords}, months={months}")
            results = search_news(keywords, months)
            print(json.dumps(results))
        else:
            logging.error("Faltan argumentos")
            print(json.dumps({
                'status': 'error',
                'message': 'Faltan argumentos',
                'noticias': ''
            }))
    except Exception as e:
        logging.error(f"Error en main: {str(e)}")
        print(json.dumps({
            'status': 'error',
            'message': f'Error en script: {str(e)}',
            'noticias': ''
        }))