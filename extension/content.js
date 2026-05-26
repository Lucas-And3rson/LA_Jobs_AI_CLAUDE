console.log("LA Jobs AI content script loaded");

let lastUrl = location.href;
let lastSavedJobUrl = null;

async function extractJobData() {

    try {

        const title =
            document.querySelector('.job-details-jobs-unified-top-card__job-title h1')
                ?.innerText?.trim() ||

            document.querySelector('h1')
                ?.innerText?.trim() ||

            '';

        const company =
            document.querySelector('.job-details-jobs-unified-top-card__company-name')
                ?.innerText?.trim() ||

            document.querySelector('.job-details-jobs-unified-top-card__primary-description-container a')
                ?.innerText?.trim() ||

            '';

        const description =
            document.querySelector('.jobs-description-content__text')
                ?.innerText?.trim() ||

            document.querySelector('.jobs-description')
                ?.innerText?.trim() ||

            '';

        const url = window.location.href;

        const jobData = {
            title,
            company,
            description,
            url
        };

        console.log("JOB DETECTED:", jobData);

        if (!title || !company) {

            console.log("Dados incompletos.");

            return;
        }

        if (lastSavedJobUrl === url) {

            console.log("Vaga já salva.");

            return;
        }

        lastSavedJobUrl = url;

        const response = await fetch(
            'http://127.0.0.1:8000/api/tracked-jobs',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jobData)
            }
        );

        if (!response.ok) {

            throw new Error(
                `HTTP ERROR: ${response.status}`
            );
        }

        const result = await response.json();

        console.log("JOB SAVED:", result);

    } catch (error) {

        console.error("API ERROR:", error);
    }
}

function detectJobPage() {

    const url = window.location.href;

    const isJobPage =
        url.includes('/jobs/') &&
        (
            url.includes('currentJobId=') ||
            url.includes('/view/')
        );

    if (!isJobPage) {
        return;
    }

    setTimeout(() => {
        extractJobData();
    }, 2500);
}

const observer = new MutationObserver(() => {

    if (location.href !== lastUrl) {

        lastUrl = location.href;

        console.log("URL CHANGED:", lastUrl);

        detectJobPage();
    }

});

observer.observe(document.body, {
    childList: true,
    subtree: true
});

detectJobPage();