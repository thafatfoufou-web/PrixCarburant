/**
 * Gère  le compteur de caractères du message
 */
document.addEventListener('DOMContentLoaded', function() {
    const messageField = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    
    if (messageField && charCount) {
        function updateCharCount() {
            const count = messageField.value.length;
            charCount.textContent = count;
            
            if (count > 1800) {
                charCount.style.color = '#dc2626'; 
            } else if (count > 1500) {
                charCount.style.color = '#f59e0b'; 
            } else {
                charCount.style.color = '#6b7280'; 
            }
        }
        
        messageField.addEventListener('input', updateCharCount);
        updateCharCount();
    }
});
