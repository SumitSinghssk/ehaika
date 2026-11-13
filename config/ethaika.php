<?php

return [

    'theme' => env('SITE_THEME', 'light'),

    'tagline' => 'Enabling responsible AI',

    'hero' => [
        'title' => 'Trust, Test, and Evaluate your AI Systems',
        'text' => 'Ethaika runs realistic load simulations against any AI agents endpoint, then automatically evaluates/scores every response for correctness, hallucination, groundedness, and security using LLM-as-a-Judge.',
        'primary' => 'Book Your Demo',
        'secondary' => 'Start Your First Experiment',
        'image' => 'images/site/simulation-engine.webp',
        'image_alt' => 'Ethaika Simulation Engine: peak traffic load profile with 5,000 concurrent users, 10.9s average response, 13.6s 95th percentile and 0.0% error rate',
    ],

    'why' => [
        'label' => 'Why Ethaika',
        'title' => 'Your LLM Gives Different Answers Every Time. How Do You Know It’s Good Enough?',
        'action' => 'Start Your First Experiment',
        'stats' => [
            ['value' => 4, 'suffix' => '+', 'label' => 'Evaluation Providers', 'text' => 'OpenAI · Anthropic · AWS Bedrock · Azure AI Foundry. Score every response automatically'],
            ['value' => 6, 'suffix' => '', 'label' => 'Judge Types', 'text' => 'Correctness · Hallucination · Groundedness · Faithfulness · Security · Toxicity'],
            ['value' => 100, 'suffix' => 's', 'label' => 'of Virtual Users', 'text' => 'Simulate realistic concurrent load against your chatbot. No infrastructure changes needed'],
        ],
    ],

    'integrations' => [
        'label' => 'Integrations',
        'title' => 'Push Evaluation Results Into Tools Your Team Already Uses.',
        'text' => [
            'Automatically export evaluation results and defect reports into the tools your engineering team already lives in. Whether it’s Jira, GitHub, or Azure DevOps.',
            'Ethaika closes the loop from LLM failure to resolved ticket, without leaving the platform.',
        ],
        'action' => 'Start Your First Experiment',
        'logos' => [
            ['name' => 'Jira', 'src' => 'images/site/logos/jira.png', 'invert' => false, 'crop' => true],
            ['name' => 'GitHub', 'src' => 'images/site/logos/github.png', 'invert' => true, 'crop' => false],
            ['name' => 'Azure DevOps', 'src' => 'images/site/logos/azure-devops.png', 'invert' => false, 'crop' => false],
            ['name' => 'Anthropic', 'src' => 'images/site/logos/anthropic.svg', 'invert' => false, 'crop' => false],
            ['name' => 'AWS Bedrock', 'src' => 'images/site/logos/aws-bedrock.png', 'invert' => false, 'crop' => false],
        ],
    ],

    'realtime' => [
        'label' => 'Real‑time Evaluation Data',
        'stats' => [
            ['value' => 4, 'suffix' => '+', 'label' => 'LLM judge providers supported'],
            ['value' => 100, 'suffix' => '+', 'label' => 'Virtual users per simulation'],
            ['value' => 6, 'suffix' => '+', 'label' => 'Evaluation metrics per turn'],
        ],
        'title' => 'Evaluation results streaming back in real time context.',
        'text' => 'Watch judge scores, pass rates, and latency metrics update live as your simulation runs — no waiting for a batch job to finish. Ethaika streams every evaluated turn back instantly so your team can act on quality issues the moment they surface.',
        'action' => 'Book Your Demo',
        'screens' => [
            ['src' => 'images/site/architecture.webp', 'name' => 'Ethaika Platform', 'alt' => 'Ethaika platform architecture: scaled simulation, cross-platform evaluation and reports connected to enterprise agents'],
            ['src' => 'images/site/trends-dashboard.webp', 'name' => 'Trends', 'alt' => 'Ethaika trends dashboard comparing evaluation runs'],
            ['src' => 'images/site/run-review.webp', 'name' => 'Run Review', 'alt' => 'Ethaika Run Review with each query, AI response, ground truth and judge scores'],
        ],
    ],

    'features' => [
        'label' => 'Features',
        'title' => 'Distinct features that truly stand out.',
        'compare' => [
            'columns' => ['Capability', 'Vendor Tools', 'Eval Frameworks', 'Observability', 'Ethaika'],
            'rows' => [
                ['Cross-Platform', ['no', 'Stack-locked'], ['part', 'Partial'], ['no', 'Monitor only'], ['yes', 'Native across models and platforms']],
                ['Vendor-Neutral', ['no', 'Biased'], ['yes', 'Technical neutrality'], ['part', 'N/A'], ['yes', 'Decision-neutral orchestration']],
                ['Non-Intrusive', ['no', 'Tight coupling'], ['part', 'SDK effort'], ['part', 'Instrumentation heavy'], ['yes', 'Works with existing enterprise stack']],
                ['Enterprise Deployable', ['part', 'Varies'], ['part', 'Developer-first'], ['part', 'Ops-first'], ['yes', 'VPC / on-prem ready']],
                ['Governance-Aware', ['part', 'Limited'], ['part', 'Metric-level only'], ['part', 'Incident-level only'], ['yes', 'Built into decision lifecycle']],
                ['Experimentation-First', ['no', 'Limited'], ['part', 'Technical only'], ['no', 'Post-deploy only'], ['yes', 'Structured pre-deployment experiments']],
                ['Optimization Loop', ['no', 'One-off choices'], ['part', 'Not business-coupled'], ['no', 'Reactive'], ['yes', 'Continuous quality / cost / governance']],
            ],
        ],
        'items' => [
            ['name' => 'Unified AI Test Workbench', 'body' => [
                'Simulate real user conversations with AI systems to test behavior, responses, and edge cases before production.',
                'Configure, visualize, and save simulation profiles to ensure consistent test runs, and generate hundreds of virtual users to replicate production workloads.',
            ]],
            ['name' => 'Multi-Framework Evaluation', 'body' => [
                'Evaluate AI systems across leading frameworks such as DeepEval, Azure AI Foundry, and Ragas through a simple configuration-driven setup.',
                'Run consistent evaluations across platforms without changing your core test definitions.',
            ]],
            ['name' => 'BYOD (Bring your own data)', 'body' => [
                'Bring your own data from simulations and leverage the best in class Eval orchestrations.',
                'Define your custom judges and compare across popular Industry platforms.',
            ]],
            ['name' => 'Enterprise-Grade Security & Integration', 'body' => [
                'Deploy the platform within your organization’s security guardrails and governance framework.',
                'Integrate your AI application endpoints using modern authentication and integration standards, while maintaining strong protections such as prompt security, strict data isolation, and encryption for data both at rest and in transit.',
            ]],
            ['name' => 'Actionable Reports & Insights', 'body' => [
                'Generate clear evaluation reports and performance dashboards to support AI quality decisions. Use our out-of-the-box analytics or customize reports to fit your needs.',
                'Easily export, share, or integrate results with your enterprise tools through standard telemetry and integration protocols.',
            ]],
            ['name' => 'Real‑Time Run Notifications', 'body' => [
                'Schedule or run in real time. We support what your needs are. Estimate, trigger and wait for our notifications in the channel of your choice.',
            ]],
        ],
    ],

    'faq' => [
        'title' => 'You asked, we answered.',
        'text' => 'Clear answers for teams building and testing AI in production.',
        'items' => [
            ['q' => 'What problem does Ethaika solve?', 'a' => ['Ethaika empowers organizations to accelerate their AI evaluation journey by providing a unified platform for simulating real user behavior, running scalable AI evaluations, and consistently measuring AI quality across models and platforms.']],
            ['q' => 'How is Ethaika different from standard logging or chat history?', 'a' => ['Traditional logs show what happened.', 'Ethaika captures why it happened — including hidden context, model settings, retrieval traces, and execution metadata — making AI behaviour reproducible instead of guesswork.']],
            ['q' => 'Can Ethaika work with non-deterministic LLMs?', 'a' => ['Yes. Ethaika is built specifically for non-deterministic systems.', 'Even when outputs vary, our deterministic replay and session capture allow teams to reliably analyze and diagnose failures.']],
            ['q' => 'Which AI systems does Ethaika support?', 'a' => ['Ethaika is model-agnostic and works with:'], 'list' => ['LLM-powered chatbots', 'AI agents and tool-using systems', 'RAG-based applications', 'Custom in-house AI services'], 'after' => 'We integrate at the API and execution layer, not just the UI.'],
            ['q' => 'Who typically uses Ethaika?', 'a' => ['Ethaika is used by:'], 'list' => ['QA teams validating AI behaviour', 'Engineers debugging agents and pipelines', 'Product teams reviewing AI releases', 'Organizations needing transparency and trust in AI systems'], 'after' => 'Everyone works from the same reproducible source of truth.'],
            ['q' => 'Can we see Ethaika in action?', 'a' => ['Yes — you can request a demo to walk through the workbench, defect capture, and replay experience using real AI workflows.']],
        ],
    ],

    'contact' => [
        'label' => 'We’re Here For You',
        'title' => 'We’re ready to listen and respond',
        'text' => 'Your voice is important to us, and we look forward to connecting with you.',
        'form_title' => 'Send us your message',
        'form_text' => 'Share a bit about your AI use case, risk or compliance question, and how Ethaika can help. A member of the team will get back to you with practical next steps.',
        'action' => 'Schedule Demo',
    ],

    'photos' => [
        'team' => ['src' => 'images/site/team-review.webp', 'alt' => 'Two people reviewing an Ethaika evaluation run on a monitor'],
        'engineers' => ['src' => 'images/site/team-engineers.webp', 'alt' => 'Two engineers working on an AI model at their desks'],
    ],

    'platform' => [
        'meta' => 'Enterprise AI Decision Intelligence: cross-platform evaluation that helps enterprises choose the best models, architectures and workflows before committing spend.',
        'title' => 'Enterprises Are Making Costly LLM Decisions Blindly',
        'intro' => [
            'Enterprise AI adoption is exploding, but most organizations still make critical AI choices through trial and error, vendor recommendations, fragmented testing, and intuition.',
            'These are increasingly multi-million-dollar decisions. Yet most enterprises still lack a structured system to decide which LLM, architecture, or workflow delivers the best business outcome.',
        ],
        'questions' => [
            ['q' => 'How do we switch models?', 'a' => 'Can a lower-cost model maintain outcomes?', 'tags' => ['Cost pressure', 'Quality risk']],
            ['q' => 'Are chatbots working well?', 'a' => 'Or adding complexity without upside?', 'tags' => ['Context quality', 'Latency impact']],
            ['q' => 'Agents or simple flows?', 'a' => 'Do agents improve outcomes enough?', 'tags' => ['Task completion', '2.4x cost risk']],
            ['q' => 'Are we overspending?', 'a' => 'Inference spend scales faster than value.', 'tags' => ['Spend growth', 'ROI uncertainty']],
        ],
        'hidden' => [
            'title' => 'The Hidden Enterprise Problem',
            'text' => 'The biggest challenge is no longer AI adoption. It is continuously optimizing AI decisions.',
            'items' => [
                ['name' => 'Models', 'lines' => ['GPT · Claude · DeepSeek', 'Open source options']],
                ['name' => 'Architectures & Workflows', 'lines' => ['RAG vs non-RAG', 'Single-agent vs multi-agent']],
                ['name' => 'Platforms', 'lines' => ['Foundry · Copilot · OpenAI · AWS Bedrock', 'Custom stacks']],
            ],
            'after' => ['Every experiment introduces tradeoffs.', 'Better quality can increase cost. Lower cost can increase hallucination risk. Faster deployment can reduce governance confidence.', 'Most teams cannot systematically compare these decisions.'],
        ],
        'cost' => [
            'title' => 'The Cost Problem Is Growing',
            'text' => 'A small model choice can dramatically affect enterprise economics, outcomes, and risk exposure.',
            'items' => [
                ['name' => 'Inference Cost', 'text' => 'Small per-call inference cost scale into millions across high-volume workflows.'],
                ['name' => 'Latency', 'text' => 'Model and architecture choices directly impact responsiveness and SLA performance.'],
                ['name' => 'Customer Experience', 'text' => 'Output quality consistency determines trust, retention, and support burden.'],
                ['name' => 'Hallucination Risk', 'text' => 'Lower-cost choices can increase failure rates in enterprise-critical scenarios.'],
                ['name' => 'Governance Readiness', 'text' => 'Wrong decisions later create governance and compliance exposure with little traceability.'],
                ['name' => 'Decision Confidence', 'text' => 'Without trusted evaluation, optimization becomes guesswork instead of evidence.'],
            ],
            'after' => 'Without a structured way to evaluate these dimensions, enterprises are optimizing AI spend on assumptions — not evidence.',
        ],
        'gaps' => [
            'title' => 'Why Existing Solutions Fall Short',
            'text' => 'Current tools solve fragments. Enterprises need a neutral decision system.',
            'items' => [
                ['name' => 'Vendor Ecosystems', 'text' => 'Typically optimize only their own stack and bias evaluation toward internal tooling.'],
                ['name' => 'Evaluation Frameworks', 'text' => 'Strong technical metrics, but limited business decision support across platforms.'],
                ['name' => 'Observability Tools', 'text' => 'Great for monitoring after deployment, not for choosing the best AI approach before rollout.'],
                ['name' => 'Governance Suites', 'text' => 'Often documentation-first, instead of experimentation-first optimization.'],
            ],
            'after' => 'No neutral system exists for structured enterprise AI evaluation across models, agents, RAG systems, copilots, and workflows.',
        ],
        'enter' => [
            'title' => 'Enter Ethaika: Cross-Platform Evaluation For Enterprise AI Decisions',
            'text' => 'Ethaika gives enterprises a structured way to experiment, evaluate, and decide — before committing spend. Three core capabilities that work together.',
            'tabs' => [
                ['name' => 'Simulation Engine', 'title' => 'Simulation Engine', 'text' => 'Run realistic load-profiled experiments before production commitment.', 'points' => ['Load profiles: Peak Traffic, Steady State, Burst / Spike, Ramp Up, Custom', '5,000 concurrent users simulated at peak load', 'Tail latency exceeds SLA at peak load: Ethaika recommends testing a lower-latency model under this load profile before production rollout.'], 'image' => 'images/site/simulation-engine.webp'],
                ['name' => 'Eval Orchestration', 'title' => 'Plug & Play Evaluation Orchestration', 'text' => 'Connect your evaluation frameworks once. Run them together on every experiment.', 'points' => ['Azure AI Foundry: Relevance · Completeness · Safety', 'DeepEval: Hallucination · Answer Relevancy', 'RAGAS: Context Recall · Faithfulness', 'Custom Judge and Promptfoo: plug-in ready', 'All connected frameworks run in parallel on every experiment. Ethaika normalises scores across frameworks into a single pass/warn/fail signal — no custom glue code required.'], 'image' => 'images/site/architecture.webp'],
                ['name' => 'Decision Dashboard', 'title' => 'Decision Dashboard', 'text' => 'Experiment results surfaced as business decisions, not raw metrics.', 'points' => ['High level summary and overall pass rate per platform', 'AI quality metrics, latency metrics, reliability and robustness', 'Token usage and cost, with the estimated cost of the next run', 'Platform comparison matrix with per-turn reasoning'], 'image' => 'images/site/trends-dashboard.webp'],
                ['name' => 'Run Review', 'title' => 'Run Review — Governance & Human-in-the-Loop', 'text' => 'Drill into every query, response, and evaluation score. Support human review before deployment.', 'points' => ['Reviewers can inspect every query, AI response, ground truth, and per-platform score before a deployment decision is approved.', 'Full audit trail maintained for governance and compliance requirements.'], 'image' => 'images/site/run-review.webp'],
            ],
            'after' => 'This transforms enterprise AI decisions from intuition to evidence.',
        ],
        'how' => [
            'title' => 'How Ethaika Works',
            'text' => 'A non-intrusive evaluation layer that connects to enterprise AI workloads via secured config — no code changes required.',
            'image' => 'images/site/architecture.webp',
            'pillars' => [
                ['name' => 'Non-Intrusive', 'text' => 'No changes to your existing AI stack or agent code.'],
                ['name' => 'Secured by Design', 'text' => 'Config-based credential isolation, VPC & on-prem ready.'],
                ['name' => 'Enterprise Scale', 'text' => 'Evaluate across all agents, models, and platforms simultaneously.'],
            ],
        ],
        'now' => [
            'title' => 'Why This Matters Right Now',
            'text' => 'Three forces are colliding.',
            'items' => [
                ['name' => 'Cost Pressure', 'text' => 'AI spend is becoming material. Inference decisions now directly impact enterprise margins.'],
                ['name' => 'AI Fragmentation', 'text' => 'Too many models, architectures, and platforms. Decision complexity is rising faster than enterprise capability.'],
                ['name' => 'Governance Pressure', 'text' => 'As AI footprint grows, enterprises increasingly need evidence before deployment and traceability after deployment.'],
            ],
            'after' => 'Together, these forces make structured evaluation mandatory, not optional.',
        ],
        'wins' => [
            'title' => 'Why Ethaika Wins',
            'text' => 'Cross-platform, vendor-neutral, experimentation-first decision intelligence.',
            'stats' => [
                ['value' => '5×', 'label' => 'Faster Evaluation Cycle', 'text' => 'vs. manual eval journeys'],
                ['value' => '∞', 'label' => 'Enterprise Platform Scale', 'text' => 'Azure · AWS Bedrock · OpenAI & more'],
                ['value' => '100%', 'label' => 'Vendor Neutral', 'text' => 'No bias toward any single platform'],
                ['value' => '0', 'label' => 'Code Changes Required', 'text' => 'Non-intrusive config-based access'],
            ],
            'after' => 'Ethaika helps enterprises continuously optimize Quality × Cost × Governance.',
        ],
        'vision' => [
            'title' => 'Vision',
            'today' => 'Structured AI evaluation.',
            'tomorrow' => 'Enterprise AI Decision Intelligence',
            'quote' => 'Ethaika becomes the trusted decision layer that helps organizations determine what AI works best, at the right cost, with the right level of governance.',
            'steps' => [['Decide', 'Best-fit AI approach'], ['Deploy', 'With confidence'], ['Optimize', 'Continuously']],
        ],
        'cta' => [
            'title' => 'Ready to Optimize Your Enterprise AI Decisions?',
            'text' => 'See how Ethaika helps you choose the best models, architectures, and workflows before committing spend.',
            'tagline' => 'Vendor-neutral. Enterprise deployable. Experimentation-first.',
            'action' => 'Schedule a Demo',
            'facts' => [['Evaluation Coverage', 'Models · Agents · RAG · Workflows'], ['Deployment', 'VPC · On-Prem · Private Cloud'], ['Decision Outputs', 'Quality · Cost · Risk Evidence']],
        ],
    ],

    'features_page' => [
        'meta' => 'A unified platform for AI simulation, evaluation and quality insights: simulate real user interactions and evaluate AI across DeepEval, Ragas and Azure AI Foundry.',
        'title' => 'A Unified Platform for AI Simulation, Evaluation, and Quality Insights',
        'text' => 'Simulate real user interactions, evaluate AI across frameworks like DeepEval, Ragas, and Azure AI Foundry, and generate actionable insights from a single configuration-driven platform.',
        'note' => 'Flexibility to use as per your liking. Simulate, Evaluation or both. We make it simple through our config!',
        'multi' => [
            'title' => 'Multi-Framework Evaluation',
            'text' => 'Evaluate AI systems across leading frameworks such as DeepEval, Azure AI Foundry, and Ragas through a simple configuration-driven setup. Run consistent evaluations across platforms without changing your core test definitions.',
            'note' => 'When you do this, don’t forget to use Ethaika’s own AI evaluation capabilities.',
            'image' => 'images/site/trends-dashboard.webp',
        ],
    ],

    'about' => [
        'meta' => 'At Ethaika, we believe powerful AI must also be transparent, testable, and accountable.',
        'title' => 'Building Trust Into Every AI Decision',
        'text' => 'At Ethaika, we believe powerful AI must also be transparent, testable, and accountable. As LLM-powered products become core to business operations, shipping without systematic evaluation is no longer an option.',
        'note_title' => 'On a personal and professional note',
        'values' => [
            ['name' => 'Committed to reliable AI systems', 'text' => ['AI is no longer experimental — it runs real businesses.', 'Our mission is to help teams build AI agents and chatbots that behave predictably, responsibly, and at scale.']],
            ['name' => 'Transparency by design', 'text' => ['We believe AI systems should never be a black box.', 'Ethaika captures prompts, context, parameters, and execution traces so teams can clearly see why an AI behaved the way it did.']],
            ['name' => 'Built for real-world complexity', 'text' => ['LLMs are non-deterministic by nature.', 'Our workbench is designed specifically to handle variability, edge cases, and evolving models — not just happy-path demos.']],
            ['name' => 'Built by practitioners', 'text' => ['Ethaika is built by engineers who’ve shipped production AI systems.', 'We’ve felt the pain of unreproducible bugs, unclear failures, and slow defect triage — so we built the tool we wished existed.']],
            ['name' => 'Focused on QA, Dev, and Trust', 'text' => ['Our platform is designed for:'], 'list' => ['QA teams validating AI behaviour', 'Developers debugging agent logic', 'Product teams ensuring safe releases'], 'after' => 'Everyone works from the same reproducible source of truth.'],
            ['name' => 'Practical, not theoretical', 'text' => ['We’re not here to lecture about AI ethics — we provide practical tooling that makes AI systems testable, explainable, and production-ready.']],
        ],
    ],

    'blog' => [
        'title' => 'Latest Updates & Insights Blog.',
        'meta' => 'Ethaika blog and news: insights on AI testing, AI evaluation, LLM-as-Judge and enterprise AI decisions.',
    ],

    'copyright' => 'All Rights Reserved Ethaika.com',

];
