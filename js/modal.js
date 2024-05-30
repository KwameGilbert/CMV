document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("results-modal");
    const span = document.getElementsByClassName("close")[0];
    const resultsButton = document.getElementById("show-results");

    resultsButton.addEventListener("click", async () => {
        const categoryId = resultsButton.getAttribute("data-category-id");
        const resultsBody = document.getElementById("results-body");
        resultsBody.innerHTML = '';

        // Fetch results from server
        const response = await fetch(`fetch_category_results.php?category_id=${categoryId}`);
        const results = await response.json();

        results.forEach(result => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${result.contestant_id}</td>
                <td>${result.contestant_name}</td>
                <td>${result.votes}</td>
            `;
            resultsBody.appendChild(row);
        });

        modal.style.display = "block";
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    });

    span.onclick = () => {
        modal.style.display = "none";
        document.body.style.overflow = 'auto'; // Restore background scrolling
    };

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
            document.body.style.overflow = 'auto'; // Restore background scrolling
        }
    };
});
