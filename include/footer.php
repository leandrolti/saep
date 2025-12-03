<!-- Rodapé -->
<footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-12">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                Sistema de Seleção para EP <i class="fa fa-heart"></i> Licença Livre
                
              </div>
            </div>
            
          </div>
        </div>
      </footer>
    </div>
  </main>
  
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
  <script>
    function validarNota(input) {
    // Obtém o valor e substitui vírgula por ponto
    let valor = input.value.replace(',', '.');
    
    // Remove caracteres que não sejam números ou ponto
    valor = valor.replace(/[^\d.]/g, '');
    
    // Garante apenas um ponto decimal
    const partes = valor.split('.');
    if (partes.length > 2) {
        valor = partes[0] + '.' + partes.slice(1).join('');
    }
    
    // Limita a 2 casas decimais
    if (partes.length === 2 && partes[1].length > 2) {
        valor = partes[0] + '.' + partes[1].substring(0, 2);
    }
    
    // Atualiza o campo
    input.value = valor;
    
    // Valida o intervalo (0 a 10)
    if (valor !== '' && valor !== '.') {
        const numero = parseFloat(valor);
        
          if (numero < 0) {
              input.value = '0';
          } else if (numero > 10) {
              input.value = '10';
          }
        }
    }

    // Validação adicional no blur (quando sair do campo)
    function validarNotaFinal(input) {
        let valor = input.value.replace(',', '.');
        
        if (valor !== '' && valor !== '.') {
            const numero = parseFloat(valor);
            
            if (!isNaN(numero)) {
                // Garante que está no intervalo correto
                if (numero < 0) {
                    input.value = '0';
                } else if (numero > 10) {
                    input.value = '10';
                } else {
                    // Formata com até 2 casas decimais
                    input.value = numero.toString();
                }
            } else {
                input.value = '';
            }
        }
    }
  </script>
</body>

</html>